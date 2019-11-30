<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
// import mysql connection
include $rootPath . '/backend/conn.php';
// support function


// $path = $rootPath . "/data/csv/BirthTest.csv";
$path = $rootPath . "/data/csv/birth.csv";
$file = fopen($path, 'r');




$count = 0;

$sqlBirth = InitSqlBirth();
while ($data = fgetcsv($file)) {
    $count++;
    $fname = addslashes($data[2]);
    $pname = addslashes($data[3]);
    $lname = addslashes($data[4]);
    $place = addslashes($data[7]);
    $sqlBirth .= "('$data[1])',
                    '$fname',
                    '$pname',
                    '$lname',
                    '$data[5]',
                    '$data[6]',
                    '$place',
                    '$data[8]',
                    '$data[9]',
                    '$data[10]',
                    '$data[11]'),";
    if ($count % 5000 == 0) {

        $conn = OpenConn();

        $sqlBirth = substr($sqlBirth, 0, strlen($sqlBirth) - 1);
        if ($conn->query(($sqlBirth))) {
            $sqlBirth = InitSqlBirth();
        } else {
            echo "<br>" . $conn->error;
        }

        CloseConn($conn);
        sleep(10);
    }
}
if ($sqlBirth != InitSqlBirth()) {

    $conn = OpenConn();

    $sqlBirth = substr($sqlBirth, 0, strlen($sqlBirth) - 1);
    if ($conn->query(($sqlBirth))) {
        echo 'birth Table Done';
    } else {
        echo "<br>" . $conn->error;
    }

    CloseConn($conn);
}

print_r($count);



function InitSqlBirth()
{
    return "insert into birth (pid, fname, pname, lname, relation, repid, place, year, month, day, gender) values";
}
