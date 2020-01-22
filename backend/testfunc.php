<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include $rootPath . '/backend/sundryFunction.php';

function OpenConn()
{
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

function CloseConn($conn)
{
    $conn->close();
}




function GetDateDictOfYear($year)
{
    $range = array();
    $start = new DateTime($year . '-01-01');
    $end = new DateTime($year . '-12-31');
    do {
        $range[$start->format('Y-m-d')] = 0;
        $start->add(new DateInterval('P1D'));
    } while ($start <= $end);

    return $range;
}

function GetFreqPerDay($table,$year){
    $outputArr=GetDateDictOfYear($year);
    $sql = "SELECT count(*) AS count, month, day FROM $table WHERE year='$year' GROUP BY month, day;";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $date = new DateTime($year. '-' . $row['month'] . '-' . $row['day']);
            $outputArr[$date->format('Y-m-d')] = $row['count'];
        }     
    }
    CloseConn($conn);
    return json_encode($outputArr);
}

print_r(GetFreqPerDay('birth_s',1840));
