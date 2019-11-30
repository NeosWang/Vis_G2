<?php
$start = 1;
$end = 10000;

QueryAndStore($start, $end);

function QueryAndStore($start, $amount)
{
        $end = $start + $amount;
        $fp = fopen('birth.csv', 'a');
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $db = "bhic";
        $con = new mysqli($dbhost, $dbuser, $dbpass, $db) or die("Connect failed: %s\n" . $con->error);
        $sql = 'SELECT * FROM birth WHERE id>= ' . $start . ' AND id<' . $end . ';';
        $result = mysqli_query($con, $sql);
        $count = 0;
        while ($row = $result->fetch_assoc()) {
                $count++;
                fputcsv($fp, $row);
        }
        fclose($fp);
        $con->close();
        if ($count >= $amount) {
                QueryAndStore($end, $amount);
        }
}
