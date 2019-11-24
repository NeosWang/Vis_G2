<?php


function GetGenderInt($gender)
{
    switch ($gender) {
        case 'Vrouw':
            return 0;
            break;
        case 'Man':
            return 1;
            break;
        default:
            return 2;
    }
}