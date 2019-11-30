<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include $rootPath . '/backend/conn.php';

$conn=OpenConn();

$sql = 'SELECT * FROM birth';
$result = mysqli_query($conn, $sql);
$count = 0;
while ($row = $result->fetch_assoc()) {
        print_r($row);
}


CloseConn($conn);
