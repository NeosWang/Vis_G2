<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';
include 'parseTool.php';

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

    while ($xml->read()) {

        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
            $count++;

            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            $node = simplexml_load_string($xmlStr);

            ParseBirthRecord($node, $sqlBirth);

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
    return "insert into geboorte (pid, fname, pname, lname, year, month, day, gender, region) values";
}


function ParseBirthRecord($node, &$sqlBirth)
{
    $pid = '';
    $fname='';
    $pname='';
    $lname='';
    $year = '';
    $month = '';
    $day = '';
    $region = '';
    $gender = '';

    foreach ($node->metadata->A2A->children() as $child) {

        if ($child->getName() == 'Person') {
            if(isset($child->BirthDate)){
                $pid = str_replace('Person:', '', $child['pid']);
                $fname = isset($child->PersonName->PersonNameFirstName) ? addslashes($child->PersonName->PersonNameFirstName) : null;
                $pname = isset($child->PersonName->PersonNamePrefixLastName) ? addslashes($child->PersonName->PersonNamePrefixLastName) : null;
                $lname = isset($child->PersonName->PersonNameLastName) ? addslashes($child->PersonName->PersonNameLastName) : null;
                $gender = GetGenderInt($child->Gender);
            }
        }
        elseif ($child->getName() == 'Event') {
            $year = isset($child->EventDate->Year) ? $child->EventDate->Year : 0;
            $month = isset($child->EventDate->Month) ? $child->EventDate->Month : 0;
            $day = isset($child->EventDate->Day) ? $child->EventDate->Day : 0;
        } elseif ($child->getName() == 'Source') {
            $region = addslashes($child->SourcePlace->Place);
        }
    }
    $sqlBirth .= "('$pid','$fname','$pname','$lname','$year','$month','$day','$gender','$region'),";
}
