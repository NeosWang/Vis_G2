<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
// support function
include  $rootPath . '/backend/parseTool.php';
// import genderDetecotr
include $rootPath . '/vendor/autoload.php';


// $path = $rootPath . "/data/csv/BirthTest.csv";
$path = $rootPath . "/data/csv/1850_dataset_age.csv";
$file = fopen($path, 'r');


$detector = new GenderDetector\GenderDetector();

$count = 0;
$lable = ['0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74', '75-79', '80-84', '85-89', '90-94', '95-99', '100+'];


$male = initData($lable);
$female = initData($lable);

while ($data = fgetcsv($file)) {




    $count++;
    $data = explode('|', $data[0]);
    $gender = $data[4];
    $fname = $data[1];
    $gender = GetPredictedGenderInt($detector, $fname, $gender);
    $age = 1850 - explode('-', $data[7])[0];
    $category = $age % 5;
    if ($gender == 0) {
        $female[$category]++;
    } elseif ($gender = 1) {
        $male[$category]++;
    }
    if ($count > 10) {
        goto end;
    }
}
end: echo 'end';
print_r($lable);



function initData($lable)
{
    $len = count($lable);
    $output = array();
    for ($i = 0; $i < $len; $i++) {
        array_push($output, 0);
    }
    return $output;
}
