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

    $sqlBirth = InitSqlBirth();

    $detector = new GenderDetector\GenderDetector();

    while ($xml->read()) {

        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
            $count++;

            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            $node = simplexml_load_string($xmlStr);

            ParseBirthRecord($node, $sqlBirth, $detector);

            if ($count % 50000 == 0) {
                $sqlBirth = substr($sqlBirth, 0, strlen($sqlBirth) - 1);
                if ($conn->query(($sqlBirth))) {
                    $sqlBirth = InitSqlBirth();
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
    if ($sqlBirth != InitSqlBirth()) {
        $sqlBirth = substr($sqlBirth, 0, strlen($sqlBirth) - 1);
        if ($conn->query(($sqlBirth))) {
            echo 'geboorte Table Done';
        } else {
            echo "<br>" . $conn->error;
        }
    }
    end: $xml->close();
    CloseConn($conn);
    print_r($count);
}


function InitSqlBirth()
{
    return "insert into birth (pid, fname, pname, lname, relation, repid, place, year, month, day, gender) values";
}

function ParseBirthRecord($node, &$sqlBirth, $detector)
{
    $family = array();
    $repid = '';
    $place = '';

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

            $year = isset($child->BirthDate->Year) ?
                addslashes($child->BirthDate->Year) : 0;
            $month = isset($child->BirthDate->Month) ?
                addslashes($child->BirthDate->Month) : 0;
            $day = isset($child->BirthDate->Day) ?
                addslashes($child->BirthDate->Day) : 0;

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
            $kpid = str_replace('Person:', '', $child->PersonKeyRef);
            $relation = GetRelationInt($child->RelationType);
            $family[$kpid]['relation'] = $relation;
            if ($relation == 2) {
                $repid = $kpid;
            }
        } elseif ($child->getName() == 'Source') {
            $place = addslashes($child->SourcePlace->Place);
        }
    }

    foreach ($family as $key => $value) {
        $sqlBirth .= "('$key',
                    '{$value['fname']}',
                    '{$value['pname']}',
                    '{$value['lname']}',
                    '{$value['relation']}',
                    '$repid',
                    '$place',
                    '{$value['year']}',
                    '{$value['month']}',
                    '{$value['day']}',
                    '{$value['gender']}'),";
    }
}
