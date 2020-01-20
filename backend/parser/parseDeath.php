<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
// import mysql connection
include $rootPath . '/backend/api.php';
// support function
include  $rootPath . '/backend/parseTool.php';
// import genderDetecotr
include $rootPath . '/vendor/autoload.php';
// initialize redis clinet
require $rootPath . "/lib/predis/autoload.php";

$time_start = microtime(true);
$path = $rootPath . "/data/xml/bhic_a2a_bs_o-201911.xml";
// $path = $rootPath . "/data/xml/testing_death.xml";

ParseDeath($path);
$time_end = microtime(true);
print_r('<br>' . date("i:s.u", $time_end - $time_start) . '<br>');



function ParseDeath($path)
{
    $count = 0;

    $xml = new XMLReader();
    $xml->open($path, 'UTF-8');
    $conn = OpenConn();

    $sqlDeath = InitSqlDeath();

    $detector = new GenderDetector\GenderDetector();

    $redis =  new Predis\Client();

    while ($xml->read()) {

        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
            $count++;

            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            $node = simplexml_load_string($xmlStr);

            ParseDeathRecord($node, $sqlDeath, $detector,$redis);

            if ($count % 50000 == 0) {
                $sqlDeath = substr($sqlDeath, 0, strlen($sqlDeath) - 1);
                if ($conn->query(($sqlDeath))) {
                    $sqlDeath = InitSqlDeath();
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
    if ($sqlDeath != InitSqlDeath()) {
        $sqlDeath = substr($sqlDeath, 0, strlen($sqlDeath) - 1);
        if ($conn->query(($sqlDeath))) {
            echo 'geboorte Table Done';
        } else {
            echo "<br>" . $conn->error;
        }
    }
    end: $xml->close();
    CloseConn($conn);
    print_r($count);
}


function InitSqlDeath()
{
    return "insert into death (pid, fname, pname, lname, place, year, month, day, gender) values";
}

function ParseDeathRecord($node, &$sqlDeath, $detector,$redis)
{
    $pid = '';
    $fname = '';
    $pname = '';
    $lname = '';
    $year = '';
    $month = '';
    $day = '';
    $place = '';
    $gender = '';

    $family = array();

    foreach ($node->metadata->A2A->children() as $child) {
        if ($child->getName() == 'Person') {
            array_push($family,PersonNodeToArray($child));
        } elseif ($child->getName() == 'RelationEP') {
            if ($child->RelationType == 'Overledene') {
                $pid = str_replace('Person:', '', $child->PersonKeyRef);
            }
        } elseif ($child->getName() == 'Event') {
            $year = isset($child->EventDate->Year) ? $child->EventDate->Year : 0;
            $month = isset($child->EventDate->Month) ? $child->EventDate->Month : 0;
            $day = isset($child->EventDate->Day) ? $child->EventDate->Day : 0;
            $place = isset($child->EventPlace->Place) ? addslashes($child->EventPlace->Place) : '';
        }
    }

    foreach($family as $person){
        if($pid==$person['pid']){
            $fname=$person['fname'];
            $lname=$person['lname'];
            $pname=$person['pname'];
            $gender=$person['gender'];
        }
    }
    $gender=GetPredictedGenderInt($detector,$fname,$gender,$redis);
    $sqlDeath .= "('$pid',
                '$fname',
                '$pname',
                '$lname',
                '$place',
                '$year',
                '$month',
                '$day',
                '$gender'),";
}


function PersonNodeToArray($person)
{ 
    $arrPerson=array();
    $arrPerson['pid'] = str_replace('Person:', '', $person['pid']);
    $arrPerson['fname'] = isset($person->PersonName->PersonNameFirstName) ?
                        addslashes($person->PersonName->PersonNameFirstName) : '';
    $arrPerson['pname'] = isset($person->PersonName->PersonNamePrefixLastName) ?
                        addslashes($person->PersonName->PersonNamePrefixLastName) : '';
    $arrPerson['lname'] = isset($person->PersonName->PersonNameLastName) ?
                        addslashes($person->PersonName->PersonNameLastName) : '';
    $arrPerson['gender'] = isset ($person->Gender)?$person->Gender:'';
    return $arrPerson;
}