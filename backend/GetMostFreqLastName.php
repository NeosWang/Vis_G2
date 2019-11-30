<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';

$output=GetFreqLastName('birth','desc');
echo json_encode($output);