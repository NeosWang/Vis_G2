<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include 'conn.php';
include 'sundryFunction.php';

// number of people birth and death per year from 1801
$birthArr = GetNrPerYear('geboorte');
$deathArr = GetNrPerYear('overlijden');

$sets = array($birthArr, $deathArr);

// unify the interval as x-axis for both birth and death
$xAxis = GetUnionXScale($sets);

$legend = array('Birth', 'Death');

// get the discrete series data under interval
$birthSeries = GetSeriesData($xAxis, $birthArr);
$deathSeries = GetSeriesData($xAxis, $deathArr);

$resDirthAndDeath = array('xAxis' => $xAxis, 'legend' => $legend, 'series' => array('Birth' => $birthSeries, 'Death' => $deathSeries));

//research paper by University of Groningen
//https://www.rug.nl/research/portal/files/15865622/articlesardinie21sep2014.pdf
//north brabant estimates populations: cities 5100 & countryside 221000 on 1800
$basicPop = 51000 + 221000;

$xAxisPop = GetIntersectionXScale($sets);
$popSeries=GetSeriesPopData($xAxisPop,$birthArr,$deathArr,$basicPop);




$resPop=array('xAxis' => $xAxisPop, 'legend' => 'having lived', 'series' => $popSeries);
$res=array('birthAndDeath'=>$resDirthAndDeath,'havingLived'=>$resPop);
echo json_encode($res);



