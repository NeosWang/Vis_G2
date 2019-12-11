<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
// import mysql connection
include $rootPath . '/backend/conn.php';
// support function
include  $rootPath . '/backend/parseTool.php';
// import genderDetecotr
include $rootPath . '/vendor/autoload.php';

$time_start = microtime(true);
$path = $rootPath . "/data/xml/bhic_a2a_bs_g-201911.xml";
// $path = $rootPath . "/data/xml/testing_birth.xml";

ParseBirth($path);
$time_end = microtime(true);
print_r('<br>' . date("i:s.u", $time_end - $time_start) . '<br>');



function ParseBirth($path)
{
    $count = 0;

    $xml = new XMLReader();
    $xml->open($path, 'UTF-8');
    $conn = OpenConn();

    $sqlBirthS = InitSqlBirthSelf();
    $sqlBirthR = InitSqlBirthRelation();

    $detector = new GenderDetector\GenderDetector();

    while ($xml->read()) {

        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
            $count++;

            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            $node = simplexml_load_string($xmlStr);

            ParseBirthRecord($node, $sqlBirthS, $sqlBirthR, $detector);

            if ($count % 50000 == 0) {
               BulkInsert($sqlBirthS,InitSqlBirthSelf(),$conn);
               BulkInsert($sqlBirthR,InitSqlBirthRelation(),$conn);
            }
            unset($node);
            // jump to next <record>
            $xml->next('record');
        }
    }

    if ($sqlBirthS != InitSqlBirthSelf()) {
        BulkInsert($sqlBirthS,InitSqlBirthSelf(),$conn);
    }

    if ($sqlBirthR != InitSqlBirthRelation()) {
        BulkInsert($sqlBirthR,InitSqlBirthRelation(),$conn);
    }

    end: $xml->close();
    CloseConn($conn);
    print_r($count);
}


function InitSqlBirthSelf()
{
    return "insert into birth_s (rid, fname, pname, lname, place, year, month, day, gender) values";
}

function InitSqlBirthRelation()
{
    return "insert into birth_r (rid, fname, pname, lname, relation) values";
}

function BulkInsert(&$sqlStr,$initSqlStr,$conn){
    $sqlStr = substr($sqlStr, 0, strlen($sqlStr) - 1);
    if ($conn->query(($sqlStr))) {
        $sqlStr = $initSqlStr;
    } else {
        echo "<br>" . $conn->error;
    }
}


function ParseBirthRecord($node, &$sqlBirthS, &$sqlBirthR, $detector)
{
    $family = array();
    $place = '';
    $year='';
    $month='';
    $day='';

    $rid = isset($node->header->identifier) ?
        addslashes($node->header->identifier) : '';

    foreach ($node->metadata->A2A->children() as $child) {

        if ($child->getName() == 'Person') {

            $pid = str_replace('Person:', '', $child['pid']);
            $fname = isset($child->PersonName->PersonNameFirstName) ?
                addslashes($child->PersonName->PersonNameFirstName) : '';
            $pname = isset($child->PersonName->PersonNamePrefixLastName) ?
                addslashes($child->PersonName->PersonNamePrefixLastName) : '';
            $lname = isset($child->PersonName->PersonNameLastName) ?
                addslashes($child->PersonName->PersonNameLastName) : '';
            $gender = GetPredictedGenderInt($detector, $fname, $child->Gender);
            $family[$pid] = array(
                'fname' => $fname,
                'pname' => $pname,
                'lname' => $lname,
                'gender' => $gender
            );
        } elseif ($child->getName() == 'RelationEP') {
            $kpid = str_replace('Person:', '', $child->PersonKeyRef);
            $relation = GetRelationInt($child->RelationType);
            $family[$kpid]['relation'] = $relation;
        } elseif ($child->getName() == 'Source') {
            $place = addslashes($child->SourcePlace->Place);
        } elseif($child->getName()=='Event'){
            $year = isset($child->EventDate->Year) ?
                addslashes($child->EventDate->Year) : 0;
            $month = isset($child->EventDate->Month) ?
                addslashes($child->EventDate->Month) : 0;
            $day = isset($child->EventDate->Day) ?
                addslashes($child->EventDate->Day) : 0;
        }
    }

    foreach ($family as $key => $value) {
        if ($value['relation'] == 2) {
            $sqlBirthS .= "('$rid',
                            '{$value['fname']}',
                            '{$value['pname']}',
                            '{$value['lname']}',    
                            '$place',
                            '$year',
                            '$month',
                            '$day',
                            '{$value['gender']}'),";
        } else {
            $sqlBirthR .= "('$rid',
                            '{$value['fname']}',
                            '{$value['pname']}',
                            '{$value['lname']}',    
                            '{$value['relation']}'),";
        }
    }
}

function GetRelationInt($relation)
{
    switch ($relation) {
        case 'Kind':
            return 2;
            break;
        case 'Vader':
            return 1;
            break;
        case 'Moeder':
            return 0;
            break;
        default:
            return 3;
    }
}
