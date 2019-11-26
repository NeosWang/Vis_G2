<?php
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

function GetSeriesPopData($xAxis, $birthArr, $deathArr, $basicPop)
{
    $output = array();
    foreach ($xAxis as $year) {
        if (array_key_exists($year, $birthArr)) {
            $basicPop += $birthArr[$year];
        }
        if (array_key_exists($year, $deathArr)) {
            $basicPop -= $deathArr[$year];
        }
        array_push($output, $basicPop);
    }
    return $output;
}


function GetUnionXScale($sets)
{
    if (count($sets) > 0) {
        $start = key($sets[0]);
        end($sets[0]);
        $end = key($sets[0]);
        reset($sets[0]);

        foreach ($sets as $set) {
            $start = $start < key($set) ? $start : key($set);
            end($set);
            $end = $end > key($set) ? $end : key($set);
        }
        $output = array();
        for ($i = $start; $i <= $end; $i++) {
            array_push($output, $i);
        }
        return $output;
    }
}



function GetIntersectionXScale($sets)
{
    if (count($sets) > 0) {
        $start = key($sets[0]);
        end($sets[0]);
        $end = key($sets[0]);
        reset($sets[0]);

        foreach ($sets as $set) {
            $start = $start > key($set) ? $start : key($set);
            end($set);
            $end = $end < key($set) ? $end : key($set);
        }
        $output = array();
        for ($i = $start; $i <= $end; $i++) {
            array_push($output, $i);
        }
        return $output;
    }
}