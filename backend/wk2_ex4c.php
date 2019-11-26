<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';


$femaleBirthArr=GetNrPerYearByGender('geboorte',0);
$maleBirthArr=GetNrPerYearByGender('geboorte',1);

$femaleDeathArr=GetNrPerYearByGender('overlijden',0);
$maleDeathArr=GetNrPerYearByGender('overlijden',1);




$itemsBirth = array($femaleBirthArr, $maleBirthArr);

$xAxisBirth = GetYearArray($itemsBirth);
$legendBirth = array('female', 'male');

$femaleBirthSeries = GetSeriesData($xAxisBirth, $femaleBirthArr);
$maleBirthSeries = GetSeriesData($xAxisBirth, $maleBirthArr);

$resBirth = array('xAxis' => $xAxisBirth, 'legend' => $legendBirth, 'series' => array('female' => $femaleBirthSeries, 'male' => $maleBirthSeries));




$itemsDeath = array($femaleDeathArr, $maleDeathArr);

$xAxisDeath = GetYearArray($itemsDeath);
$legendDeath = array('female', 'male');

$femaleDeathSeries = GetSeriesData($xAxisDeath, $femaleDeathArr);
$maleDeathSeries = GetSeriesData($xAxisDeath, $maleDeathArr);

$resDeath = array('xAxis' => $xAxisDeath, 'legend' => $legendDeath, 'series' => array('female' => $femaleDeathSeries, 'male' => $maleDeathSeries));

$resAll=array('birth'=>$resBirth,'death'=>$resDeath);


echo json_encode($resAll);










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