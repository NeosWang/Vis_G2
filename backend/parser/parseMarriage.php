<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
// import mysql connection
include $rootPath . '/backend/api.php';
// support function
include  $rootPath . '/backend/parseTool.php';
// initialize redis clinet
require $rootPath . "/lib/predis/autoload.php";

$time_start = microtime(true);
$path = $rootPath . "/data/xml/bhic_a2a_bs_h-201911.xml";
// $path = $rootPath . "/data/xml/testing_marriage.xml";

ParseMarriage($path);
$time_end = microtime(true);
print_r('<br>' . date("i:s.u", $time_end - $time_start) . '<br>');



function ParseMarriage($path)
{
    $count = 0;
    $arrNameGender = array();

    $xml = new XMLReader();
    $xml->open($path, 'UTF-8');
    $conn = OpenConn();

    $sqlMarriage = InitSqlMarriage();

    $redis =  new Predis\Client();

    while ($xml->read()) {

        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
            $count++;

            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            $node = simplexml_load_string($xmlStr);

            ParseMarriageRecord($node, $sqlMarriage, $arrNameGender);

            if ($count % 50000 == 0) {
                $sqlMarriage = substr($sqlMarriage, 0, strlen($sqlMarriage) - 1);
                if ($conn->query(($sqlMarriage))) {
                    $sqlMarriage = InitSqlMarriage();
                } else {
                    echo "<br>" . $conn->error;
                    goto end;
                }
            }
            unset($node);
            // jump to next <record>
            $xml->next('record');
        }
    }
    if ($sqlMarriage != InitSqlMarriage()) {
        $sqlMarriage = substr($sqlMarriage, 0, strlen($sqlMarriage) - 1);
        if ($conn->query(($sqlMarriage))) {
            echo 'marriage Table Done';
        } else {
            echo "<br>" . $conn->error;
        }
    }
    
    end: $xml->close();
    CloseConn($conn);
    UpdateRedis($arrNameGender,$redis);
   
    // print_r($count);
}


function InitSqlMarriage()
{
    return "insert into marriage (pid, fname, pname, lname, relation, rid, place, year, month, day, gender, eyear, emonth, eday) values";
}

function ParseMarriageRecord($node, &$sqlMarriage, &$arrNameGender)
{
    $rid = $node->header->identifier;

    $family = array();
    $place = '';
    $eyear = '';
    $emonth = '';
    $eday = '';

    foreach ($node->metadata->A2A->children() as $child) {

        if ($child->getName() == 'Person') {

            $pid = str_replace('Person:', '', $child['pid']);
            $fname = isset($child->PersonName->PersonNameFirstName) ?
                addslashes($child->PersonName->PersonNameFirstName) : '';
            $pname = isset($child->PersonName->PersonNamePrefixLastName) ?
                addslashes($child->PersonName->PersonNamePrefixLastName) : '';
            $lname = isset($child->PersonName->PersonNameLastName) ?
                addslashes($child->PersonName->PersonNameLastName) : '';

            $gender = isset($child->Gender) ? $child->Gender : '';
            $year = isset($child->BirthDate->Year) ?
                $child->BirthDate->Year : 0;
            $month = isset($child->BirthDate->Month) ?
                $child->BirthDate->Month : 0;
            $day = isset($child->BirthDate->Day) ?
                $child->BirthDate->Day : 0;

            $family[$pid] = array(
                'fname' => $fname,
                'pname' => $pname,
                'lname' => $lname,
                'year' => $year,
                'month' => $month,
                'day' => $day,
                'gender' => $gender
            );
        } elseif ($child->getName() == 'RelationEP') {
            $pid = str_replace('Person:', '', $child->PersonKeyRef);
            $relation = GetRelationInt($child->RelationType);

            $family[$pid]['relation'] = $relation;
        } elseif ($child->getName() == 'Event') {
            $eyear = isset($child->EventDate->Year) ?
                $child->EventDate->Year : 0;
            $emonth = isset($child->EventDate->Month) ?
                $child->EventDate->Month : 0;
            $eday = isset($child->EventDate->Day) ?
                $child->EventDate->Day : 0;
            $place = isset($child->EventPlace->Place) ?
                addslashes($child->EventPlace->Place) : '';
        }
    }

    foreach ($family as $key => $value) {
        $gender = $value['gender'] == 0 ? $value['relation'] % 2 : $value['gender'];
        $sqlMarriage .= "('$key',
                    '{$value['fname']}',
                    '{$value['pname']}',
                    '{$value['lname']}',
                    '{$value['relation']}',
                    '$rid',
                    '$place',
                    '{$value['year']}',
                    '{$value['month']}',
                    '{$value['day']}',
                    '$gender',
                    '$eyear',
                    '$emonth',
                    '$eday'),";
        UpdateNameGenderDict($arrNameGender, $value['fname'], $gender);
    }
}

function GetRelationInt($relation)
{
    switch ($relation) {
        case 'Bruidegom':
            return 1;
            break;
        case 'Vader van de bruidegom':
            return 3;
            break;
        case 'Vader van de bruid':
            return 5;
            break;
        case 'Bruid':
            return 2;
            break;
        case 'Moeder van de bruidegom':
            return 4;
            break;
        case 'Moeder van de bruid':
            return 6;
            break;
        default:
            return 0;
    }
}

function UpdateNameGenderDict(&$dict, $fname, $gender)
{
    $g = ($gender == 1 ? 1 : -1);
    if (array_key_exists($fname, $dict)) {
        $dict[$fname] += $g;
    } else {
        $dict[$fname] = $g;
    }
}

function UpdateRedis(&$dict, $redis)
{
    foreach($dict as $key =>$value){
        $redis ->set($key,($value>0?1:0));
    }
}
