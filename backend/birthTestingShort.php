<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';


$time_start = microtime(true);
$count = 0;
$countKid=0;

$path = $rootPath . "/data/xml/bhic_a2a_bs_g-201911.xml";
// $path=$rootPath.'/data/xml/testing_birth.xml';
XmlParser($path, $count,$countKid);

$time_end = microtime(true);

print_r('<br>' . date("i:s.u", $time_end - $time_start) . '<br>');
print_r($count . ' records<br>');
print_r($countKid . ' kids<br>');


function GetGenderInt($gender)
{
    if ($gender == 'Vrouw') {
        $gender = 0;
    } elseif ($gender == 'Man') {
        $gender = 1;
    } else {
        $gender = 2;
    }
    return $gender;
}

function ParseBirthRecord($node, &$sqlPerson, &$sqlBirth, &$countKid)
{
    $idk = '';
    $year = 0;
    $month = 0;
    $day = 0;
    $region = '';
    $idm = '';
    $idf = '';



    foreach ($node->metadata->A2A->children() as $child) {
        if ($child->getName() == 'Person') {
            $pid = str_replace('Person:', '', $child['pid']);
            $fname = isset($child->PersonName->PersonNameFirstName) ? addslashes($child->PersonName->PersonNameFirstName) : null;
            $pname = isset($child->PersonName->PersonNamePrefixLastName) ? addslashes($child->PersonName->PersonNamePrefixLastName) : null;
            $lname = isset($child->PersonName->PersonNameLastName) ? addslashes($child->PersonName->PersonNameLastName) : null;
            $gender = GetGenderInt($child->Gender);
            $sqlPerson .= "('$pid','$fname','$pname','$lname','$gender'),";
        } elseif ($child->getName() == 'Event') {
            $year = isset($child->EventDate->Year) ? $child->EventDate->Year : null;
            $month = isset($child->EventDate->Month) ? $child->EventDate->Month : null;
            $day = isset($child->EventDate->Day) ? $child->EventDate->Day : null;
        } elseif ($child->getName() == 'RelationEP') {
            if ($child->RelationType == 'Moeder') {
                $idm = str_replace('Person:', '', $child->PersonKeyRef);
            } elseif ($child->RelationType == 'Vader') {
                $idf = str_replace('Person:', '', $child->PersonKeyRef);
            } elseif ($child->RelationType == 'Kind') {
                $idk = str_replace('Person:', '', $child->PersonKeyRef);
            }
        } elseif ($child->getName() == 'Source') {
            $region = addslashes($child->SourcePlace->Place);
        }
    }
    $sqlBirth .= "('$idk','$year','$month','$day','$region','$idm','$idf'),";
    $countKid++;
}


/*
 *@ 
 *@ param:  
 *@ return: 
*/
function XmlParser($path, &$count,&$countKid)
{

    $xml = new XMLReader();
    $xml->open($path, 'UTF-8');
    $conn = OpenConn();

    $sqlPerson = InitSqlPerson();
    $sqlBirth = InitSqlBirth();
    while ($xml->read()) {

        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
            $count++;

            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            $node = simplexml_load_string($xmlStr);

            ParseBirthRecord($node, $sqlPerson, $sqlBirth, $countKid);

            if ($count % 100000 == 0) {
                $sqlPerson = substr($sqlPerson, 0, strlen($sqlPerson) - 1);
                $sqlBirth = substr($sqlBirth, 0, strlen($sqlBirth) - 1);
                if ($conn->query(($sqlPerson))) {
                    $sqlPerson = InitSqlPerson();
                } else {
                    echo "<br>" . $conn->error;
                    goto end;
                }
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
    if ($sqlPerson != InitSqlPerson()) {
        $sqlPerson = substr($sqlPerson, 0, strlen($sqlPerson) - 1);
        if ($conn->query(($sqlPerson))) {
            echo 'Person Table Done';
        } else {
            echo "<br>" . $conn->error;
        }
    }
    if ($sqlBirth != InitSqlBirth()) {
        $sqlBirth = substr($sqlBirth, 0, strlen($sqlBirth) - 1);
        if ($conn->query(($sqlBirth))) {
            echo 'Birth Table Done';
        } else {
            echo "<br>" . $conn->error;
        }
    }
    end: 
    $xml->close();
    $conn->close();
}

function InitSqlPerson()
{
    return "insert into person (pid, fname, pname, lname, gender) values";
}
function InitSqlBirth()
{
    return "insert into birth (pid, year, month, day, region, idm, idf) values";
}
