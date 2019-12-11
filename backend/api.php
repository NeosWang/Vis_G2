<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include $rootPath.'/backend/sundryFunction.php';

function OpenConn(){
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "bhic";

    // $dbhost = "studmysql01.fhict.local";
    // $dbuser = "dbi378352";
    // $dbpass = "i378352";
    // $db = "dbi378352";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
    if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
    return $conn;
}

function CloseConn($conn){
    $conn->close();
}





if (isset($_GET['func'])) {
    switch ($_GET['func']) {
        case 'GetFreqLastName':
            echo GetFreqLastName($_GET['table'], $_GET['orderby']);
            break;
        case 'GetOverview':
            echo GetOverview($_GET['table']);
            break;
        case 'GetPersonByLastName':
            echo GetPersonByLastName($_GET['table'],$_GET['lname']);
        break;
    }
}


function GetPersonBirthToDeach($lname){
    $birthPersonArr=GetPersonByLastName('birth',$lname);
    $deathPersonArr=GetPersonByLastName('death',$lname);

}



function GetPersonByLastName($table, $lname)
{
    $sql = "SELECT rid, fname, pname, year, month, day, place, gender FROM $table WHERE fname<>'N.N.' AND lname='$lname';";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    $outputArr = array();
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $record = array('id' => $row['rid'],
                            'fname' => $row['fname'],
                            'pname' => $row['pname'],
                            'lname'=>$lname,
                            'year' => $row['year'],
                            'month' => $row['month'],
                            'day' => $row['day'],
                            'place'=>$row['place'],
                            'gender'=>$row['gender']);
            array_push($outputArr,$record);
        }
    }
    CloseConn($conn);
    // $output=array($table=>$outputArr);
    return json_encode ($outputArr);
}




function GetOverview($table)
{
    // number of people birth and death per year from 1801
    $birthArr = GetNrPerYear($table);

    $sets = array($birthArr);

    // unify the interval as x-axis for both birth and death
    $xAxis = GetUnionXScale($sets);

    $legend = array($table);

    // get the discrete series data under interval
    $birthSeries = GetSeriesData($xAxis, $birthArr);
    // $deathSeries = GetSeriesData($xAxis, $deathArr);

    $resDirthAndDeath = array('xAxis' => $xAxis, 'legend' => $legend, 'series' => array('data' => $birthSeries));

    $res = array('birthAndDeath' => $resDirthAndDeath);
    return json_encode($res);
}




function GetNrPerYear($table)
{
    $sql = "SELECT year, count(*) AS count FROM $table WHERE year>1800 GROUP BY year HAVING count(*)>10 ORDER BY year;";

    if($table=='marriage'){
        $sql = "SELECT eyear AS year, count(*) AS count FROM `marriage` WHERE relation=1 and eyear>0 GROUP BY eyear HAVING count(*)>10 ORDER BY eyear;";
    }

    $conn = OpenConn();
    $sqlRes = $conn->query($sql);

    $outputArr = array();

    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $outputArr[$row['year']] = $row['count'];
        }
    }
    CloseConn($conn);
    return $outputArr;
}




function GetFreqLastName($table, $order)
{
    $sql = "SELECT lname, count(*) AS count FROM $table WHERE lname<>'N.N.' and lname<>'' and lname<>'####' GROUP BY lname ORDER BY count $order;";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    $outputArr = array();
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $record=array('name'=>$row['lname'],'value'=>$row['count']);
            array_push($outputArr,$record);
            // $outputArr[$row['lname']] = $row['count'];
        }
    }
    CloseConn($conn);
    return json_encode($outputArr);
}
