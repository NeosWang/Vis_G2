<?php
function OpenConn()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "bhic";

    // $dbhost = "studmysql01.fhict.local";
    // $dbuser = "dbi378352";
    // $dbpass = "i378352";
    // $db="dbi378352";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
    // $conn = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $conn->error);
    return $conn;
}

function CloseConn($conn)
{
    $conn->close();
}









/*
 *@ GetNrPerYear
 *@ param:  $table{str:table name}
 *@ return: array[{year:count}]
*/
function GetNrPerYear($table)
{
    $sql = "SELECT year, count(*) AS count FROM $table WHERE year>1800 GROUP BY year ORDER BY year;";
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



/*
 *@ GetNrPerYearByGender
 *@ param:  $table{str:table name},$gender{int: 0-female 1-male 2-unfixed}
 *@ return: array[{year:count}]
 *@ for looks neat, trim the small value of first couple of years
*/
function GetNrPerYearByGender($table,$gender)
{
    $sql = "SELECT year, count(*) AS count FROM $table WHERE year>0 and gender=$gender GROUP BY year HAVING count>10 ORDER BY year;";
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