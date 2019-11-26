<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';
include 'sundryFunction.php';

$femaleBirthArr=GetNrPerYearByGender('geboorte',0);
$maleBirthArr=GetNrPerYearByGender('geboorte',1);

$femaleDeathArr=GetNrPerYearByGender('overlijden',0);
$maleDeathArr=GetNrPerYearByGender('overlijden',1);




$setsBirth = array($femaleBirthArr, $maleBirthArr);

$xAxisBirth = GetUnionXScale($setsBirth);
$legend = array('female', 'male');

$femaleBirthSeries = GetSeriesData($xAxisBirth, $femaleBirthArr);
$maleBirthSeries = GetSeriesData($xAxisBirth, $maleBirthArr);

$resBirth = array('xAxis' => $xAxisBirth, 'legend' => $legend, 'series' => array('female' => $femaleBirthSeries, 'male' => $maleBirthSeries));




$setsDeath = array($femaleDeathArr, $maleDeathArr);

$xAxisDeath = GetUnionXScale($setsDeath);
$femaleDeathSeries = GetSeriesData($xAxisDeath, $femaleDeathArr);
$maleDeathSeries = GetSeriesData($xAxisDeath, $maleDeathArr);

$resDeath = array('xAxis' => $xAxisDeath, 'legend' => $legend, 'series' => array('female' => $femaleDeathSeries, 'male' => $maleDeathSeries));


//research paper by University of Groningen
//https://www.rug.nl/research/portal/files/15865622/articlesardinie21sep2014.pdf
//north brabant estimates populations: cities 5100 & countryside 221000 on 1800
$basicPop = 51000 + 221000;
//sorry, lets assume half is man and half is woman
$basicPop/=2;



$xAxisPop = GetIntersectionXScale($setsBirth);
$malePopSeries=GetSeriesPopData($xAxisPop,$maleBirthArr,$maleDeathArr,$basicPop);
$femalePopSeries=GetSeriesPopData($xAxisPop,$femaleBirthArr,$femaleDeathArr,$basicPop);
$resPop = array('xAxis' => $xAxisPop, 'legend' => $legend, 'series' => array('female' => $femalePopSeries, 'male' => $malePopSeries));

$resAll=array('birth'=>$resBirth,'death'=>$resDeath,'havingLived'=>$resPop);

echo json_encode($resAll);




