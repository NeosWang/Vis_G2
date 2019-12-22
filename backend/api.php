<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
include $rootPath . '/backend/sundryFunction.php';

function OpenConn()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $db = "bhic";

    // $dbhost = "studmysql01.fhict.local";
    // $dbuser = "dbi378352";
    // $dbpass = "i378352";
    // $db = "dbi378352";

    $conn = mysqli_connect($dbhost, $dbuser, $dbpass, $db);
    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    return $conn;
}

function CloseConn($conn)
{
    $conn->close();
}



if (isset($_GET['func'])) {
    switch ($_GET['func']) {
        case 'GetFreqLastName':
            echo GetFreqLastName($_GET['table'], $_GET['orderby']);
            break;
        case 'GetOverview':
            echo GetOverview($_GET['table']);
            break;
        case 'GetPersonByLastName':
            echo GetPersonByLastName($_GET['table'], $_GET['lname']);
            break;
        case 'test':
            echo GetFamilyTree($_GET['rid']);
            break;
    }
}


function GetPersonByLastName($table, $lname)
{
    $sql = "SELECT rid, fname, pname, year, month, day, place, gender FROM $table WHERE fname<>'N.N.' AND lname='$lname';";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    $outputArr = array();
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $record = array(
                'id' => $row['rid'],
                'fname' => $row['fname'],
                'pname' => $row['pname'],
                'lname' => $lname,
                'year' => $row['year'],
                'month' => $row['month'],
                'day' => $row['day'],
                'place' => $row['place'],
                'gender' => $row['gender']
            );
            array_push($outputArr, $record);
        }
    }
    CloseConn($conn);
    return json_encode($outputArr);
}




function GetOverview($table)
{
    // number of people birth and death per year from 1801
    $birthArr = GetNrPerYear($table);

    $sets = array($birthArr);

    // unify the interval as x-axis for both birth and death
    $xAxis = GetUnionXScale($sets);

    // get the discrete series data under interval
    $Series = GetSeriesData($xAxis, $birthArr);

    $res = array('xAxis' => $xAxis, 'legend' => $table, 'series' => array('data' => $Series));

    return json_encode($res);
}




function GetNrPerYear($table)
{
    $sql = "SELECT year, count(*) AS count FROM $table WHERE year>1800 GROUP BY year HAVING count(*)>10 ORDER BY year;";

    if ($table == 'marriage') {
        $sql = "SELECT eyear AS year, count(*) AS count FROM `marriage` WHERE relation=1 and eyear>0 GROUP BY eyear HAVING count(*)>10 ORDER BY eyear;";
    }

    $conn = OpenConn();
    $sqlRes = $conn->query($sql);

    $outputArr = array();

    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $outputArr[$row['year']] = $row['count'];
        }
    }
    CloseConn($conn);
    return $outputArr;
}




function GetFreqLastName($table, $order)
{
    $sql = "SELECT lname, count(*) AS count FROM $table WHERE lname<>'N.N.' and lname<>'' and lname<>'####' GROUP BY lname ORDER BY count $order;";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    $outputArr = array();
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $record = array('name' => $row['lname'], 'value' => $row['count']);
            array_push($outputArr, $record);
            // $outputArr[$row['lname']] = $row['count'];
        }
    }
    CloseConn($conn);
    return json_encode($outputArr);
}




function GetFamilyTree($rid){
    $dict=array();
    $res=or_test($rid,0,'',$dict);
    // $res=test('37ca2ff2-73b3-29bc-3f8d-3194e1adceda',0,'');
    return json_encode($res);
}



//结果的year小于parent才push
function test($rid, $parentYear,$parentPlace)
{
    $strPlace="AND place='$parentPlace' ";
    $year=0;
    if($parentPlace==''){
        $strPlace='';
    }
    $sql = "SELECT fname, pname, lname, year, place FROM birth_s WHERE rid='$rid' AND year>$parentYear $strPlace LIMIT 1;";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            $fname = $row['fname'];
            $pname = $row['pname'];
            $lname = $row['lname'];
            $year = $row['year'];
            $place= $row['place'];
            if($parentPlace==''){
                $parentPlace=$place;
            }
        }
    }
    CloseConn($conn);
    if ($year > $parentYear+10 && $place==$parentPlace) {
        $res = array('fname' => $fname, 'pname' => $pname, 'lname' => $lname,'year'=>$year, 'place'=>$place, 'children' => test1($fname, $pname, $lname, $year,$parentPlace));
        return $res;
    }
    return 0;
}

function or_test($rid, $parentYear,$parentPlace,&$dict){
    if(count($dict)==0){
        $sql = "SELECT * FROM birth_s WHERE rid='$rid' AND year>$parentYear LIMIT 1;";
        $conn = OpenConn();
        $sqlRes = $conn->query($sql);
        if ($sqlRes->num_rows > 0) {
            while ($row = $sqlRes->fetch_assoc()) {
                $fname = $row['fname'];
                $pname = $row['pname'];
                $lname = $row['lname'];
                $parentYear = $row['year'];
                $parentPlace= $row['place'];
                $gender=$row['gender'];
            }
        }
        CloseConn($conn);


        $sql = "SELECT * FROM birth_s WHERE year>$parentYear AND place='$parentPlace';";
        $conn = OpenConn();
        $sqlRes = $conn->query($sql);
        if ($sqlRes->num_rows > 0) {
            while ($row = $sqlRes->fetch_assoc()) {
                $dict[$row['rid']]=array('fname'=>$row['fname'],'pname'=>$row['pname'], 'lname'=>$row['lname'],'year'=>$row['year'],'gender'=>$row['gender']);
            }
        }
        CloseConn($conn);

        $res = array('fname' => $fname, 'pname' => $pname, 'lname' => $lname,'year'=>$parentYear, 'place'=>$parentPlace, 'gender'=>$gender,'children' => or_test1($fname, $pname, $lname, $parentYear,$parentPlace,$dict));
        return $res;
    }else{
        if(array_key_exists($rid,$dict)&&$dict[$rid]['year']>$parentYear+14 && $dict[$rid]['year']-$parentYear<50){
            $fname=$dict[$rid]['fname'];
            $pname=$dict[$rid]['pname'];
            $lname=$dict[$rid]['lname'];
            $parentYear=$dict[$rid]['year'];
            $gender=$dict[$rid]['gender'];
            $res = array('fname' =>$fname, 'pname' => $pname, 'lname' =>$lname,'year'=>$parentYear, 'place'=>$parentPlace, 'gender'=>$gender,'children' => or_test1($fname, $pname, $lname, $parentYear,$parentPlace,$dict));
            return $res;
        }
        else{
            return 0;
        }
    
    }
}

//找到改名字所有做父母的记录，并用出生记录找小孩,然后返回认证做的小孩
function test1($fname, $pname, $lname, $parentYear,$parentPlace)
{
    $sql = "SELECT rid from birth_r WHERE fname='$fname' AND pname='$pname' AND lname='$lname';";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    //所有可能的孩子
    $tempChildrenRid = array();
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            array_push($tempChildrenRid, $row['rid']);
        }
    }

    CloseConn($conn);

    $res=array();
    foreach ($tempChildrenRid as $rid) {
        $return=test($rid,$parentYear,$parentPlace);
        if($return!=0){
            array_push($res,$return);
        }
     }
    return $res;
}


function or_test1($fname, $pname, $lname, $parentYear,$parentPlace,&$dict){
    $sql = "SELECT rid from birth_r WHERE fname='$fname' AND pname='$pname' AND lname='$lname';";
    $conn = OpenConn();
    $sqlRes = $conn->query($sql);
    //所有可能的孩子
    $tempChildrenRid = array();
    if ($sqlRes->num_rows > 0) {
        while ($row = $sqlRes->fetch_assoc()) {
            array_push($tempChildrenRid, $row['rid']);
        }
    }
    CloseConn($conn);
    $res=array();
    foreach ($tempChildrenRid as $rid) {
        $return=or_test($rid,$parentYear,$parentPlace,$dict);
        if($return!=0){
            array_push($res,$return);
        }
     }
    return $res;
}