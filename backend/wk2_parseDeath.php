<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';
include 'parseTool.php';

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

    while ($xml->read()) {

        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
            $count++;

            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            $node = simplexml_load_string($xmlStr);

            ParseDeathRecord($node, $sqlDeath);

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
    return "insert into overlijden (pid, fname, pname, lname, year, month, day, gender, region) values";
}


function ParseDeathRecord($node, &$sqlDeath)
{
    $pid = '';
    $fname = '';
    $pname = '';
    $lname = '';
    $year = '';
    $month = '';
    $day = '';
    $region = '';
    $gender = '';

    $personArr = array();

    foreach ($node->metadata->A2A->children() as $child) {
        if ($child->getName() == 'Person') {
            array_push($personArr,PersonNodeToArray($child));
        } elseif ($child->getName() == 'RelationEP') {
            if ($child->RelationType == 'Overledene') {
                $pid = str_replace('Person:', '', $child->PersonKeyRef);
            }
        } elseif ($child->getName() == 'Event') {
            $year = isset($child->EventDate->Year) ? $child->EventDate->Year : 0;
            $month = isset($child->EventDate->Month) ? $child->EventDate->Month : 0;
            $day = isset($child->EventDate->Day) ? $child->EventDate->Day : 0;
            $region = isset($child->EventPlace->Place) ? addslashes($child->EventPlace->Place) : null;
        }
    }

    foreach($personArr as $person){
        if($pid==$person['pid']){
            $fname=$person['fname'];
            $lname=$person['lname'];
            $pname=$person['pname'];
            $gender=$person['gender'];
        }
    }
    $sqlDeath .= "('$pid','$fname','$pname','$lname','$year','$month','$day','$gender','$region'),";
}



function PersonNodeToArray($person)
{ 
    $arrPerson=array();
    $arrPerson['pid'] = str_replace('Person:', '', $person['pid']);
    $arrPerson['fname'] = isset($person->PersonName->PersonNameFirstName) ? addslashes($person->PersonName->PersonNameFirstName) : null;
    $arrPerson['pname'] = isset($person->PersonName->PersonNamePrefixLastName) ? addslashes($person->PersonName->PersonNamePrefixLastName) : null;
    $arrPerson['lname'] = isset($person->PersonName->PersonNameLastName) ? addslashes($person->PersonName->PersonNameLastName) : null;
    $arrPerson['gender'] = GetGenderInt($person->Gender);
    return $arrPerson;
}