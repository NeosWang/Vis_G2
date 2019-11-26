<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';


$birthArr=GetNrPerYear('geboorte');
$deathArr=GetNrPerYear('overlijden');

$items = array($birthArr, $deathArr);

$xAxis = GetYearArray($items);
$legend = array('Birth', 'Death');

$birthSeries = GetSeriesData($xAxis, $birthArr);
$deathSeries = GetSeriesData($xAxis, $deathArr);

$res = array('xAxis' => $xAxis, 'legend' => $legend, 'series' => array('Birth' => $birthSeries, 'Death' => $deathSeries));

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
