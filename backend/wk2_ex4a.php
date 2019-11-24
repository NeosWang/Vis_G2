<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';



$sqlBirth = "SELECT year, count(*) AS count FROM geboorte WHERE year>0 GROUP BY year ORDER BY year;";
$conn = OpenConn();
$sqlBirthRes = $conn->query($sqlBirth);

$birthArr = array();

if ($sqlBirthRes->num_rows > 0) {
    // 输出数据
    while ($row = $sqlBirthRes->fetch_assoc()) {
        $birthArr[$row['year']] = $row['count'];
    }
}
CloseConn($conn);




$sqlDeath = "SELECT year, count(*) AS count FROM overlijden WHERE year>0 GROUP BY year ORDER BY year;";
$conn = OpenConn();
$sqlDeathRes = $conn->query($sqlDeath);

$deathArr = array();

if ($sqlDeathRes->num_rows > 0) {
    // 输出数据
    while ($row = $sqlDeathRes->fetch_assoc()) {

        $deathArr[$row['year']] = $row['count'];
    }
}
CloseConn($conn);

$items = array($birthArr, $deathArr);

$xAxis = GetYearArray($items);
$legend = array('Birth', 'Death');


$birthSeries=GetSeriesData($xAxis,$birthArr);
$deathSeries=GetSeriesData($xAxis,$deathArr);

$res=array('xAxis'=>$xAxis,'legend'=>$legend,'series'=>array('Birth'=>$birthSeries,'Death'=>$deathSeries));


echo json_encode($res);







function GetSeriesData($xAxis, $arr)
{
    $output = array();
    foreach ($xAxis as $year) {
        if (array_key_exists($year, $arr)) {
            array_push($output, $arr[$year]);
        } else {
            array_push($output, null);
        }
    }
    return $output;
}



function GetYearArray($items)
{
    if (count($items) > 0) {
        $start = key($items[0]);
        end($items[0]);
        $end = key($items[0]);

        foreach ($items as $item) {
            $start = $start < key($item) ? $start : key($item);
            end($item);
            $end = $end > key($item) ? $end : key($item);
        }
        $output = array();
        for ($i = $start; $i <= $end; $i++) {
            array_push($output, $i);
        }
        return $output;
    }
}
