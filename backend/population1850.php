<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
// support function
include  $rootPath . '/backend/parseTool.php';
// import genderDetecotr
include $rootPath . '/vendor/autoload.php';


// $path = $rootPath . "/data/csv/BirthTest.csv";
$path = $rootPath . "/data/csv/1850_dataset_age.csv";
// $path = $rootPath . "/data/csv/1819_kind_only.csv";

$file = fopen($path, 'r');

// $detector = new GenderDetector\GenderDetector();
$labels = ['0-4', '5-9', '10-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50-54', '55-59', '60-64', '65-69', '70-74', '75-79', '80-84', '85-89', '90-94', '95-99', '100+'];
$male = initData($labels);
$female = initData($labels);

while ($data = fgetcsv($file)) {

    //split string to array data
    $data = explode('|', $data[0]);


    //split string date get year
    $deathYear = explode('-', $data[6])[0];
    $birthYear = explode('-', $data[7])[0];

    if ($deathYear > 1850) {
        $gender = $data[4];
        $fname = $data[1];
        //detect gender
        // $gender = GetPredictedGenderInt($detector, $fname, $gender);
        $gender=substr($fname, -1)=='a'?0:1;
        $age = 1850 - $birthYear;
        // int corresponding to the index of label
        $category =intval($age / 5);

        if ($gender == 0) {
            $female[$category]++;
        } else {
            $male[$category]++;
        }
    }
}

end: fclose($file);
$res = array('labels' => $labels, 'male' => $male, 'female' => $female);
echo json_encode($res);



function initData($lable)
{
    $len = count($lable);
    $output = array();
    for ($i = 0; $i < $len; $i++) {
        array_push($output, 0);
    }
    return $output;
}
