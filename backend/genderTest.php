<?php 
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once($rootPath.'/vendor/autoload.php');

$genderDetector = new GenderDetector\GenderDetector();

$string='Elisabeth Johanna';

print $genderDetector->detect(explode(' ', $string)[0],GenderDetector\Country::THE_NETHERLANDS);




