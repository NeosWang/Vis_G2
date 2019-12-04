<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];

function GetTrimPid($pid)
{
    $trimHead = "{'pid': 'Person:";
    $trimFoot = "'}";
    return  str_replace($trimFoot, '',  str_replace($trimHead, '', $pid));
}

function GetPredictedGenderInt($detector, $fname, $gender)
{
    if ($gender == 'Onbekend') {
        $predict = $detector->detect(explode(' ', $fname)[0], GenderDetector\Country::THE_NETHERLANDS);
        unset($detector);
        return GetGenderInt($predict);
    }
    return GetGenderInt($gender);
}



function GetGenderInt($gender)
{
    switch ($gender) {
        case 'Vrouw':
        case 'female':
        case 'mostly_female':
            return 0;
            break;
        case 'Man':
        case 'male':
        case 'mostly_male':
            return 1;
            break;
        default:
            return 2;
    }
}


