<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];

$time_start = microtime(true);

// path of target file
$path = $rootPath . "/data/xml/bhic_a2a_bs_o-201911.xml";

// count total person
$count = 0;

$nameArr = GetArrayPersonsName($path, $count);

$nameArr = DescSortByLength($nameArr);

// considering missing value on name: N.N. we take first 11
$nameArrTopN = PopularNameTopN($nameArr, 11);

foreach ($nameArrTopN as $key) {
    print_r($key . ' : ' . count($nameArr[$key]) . 'person<br>');
}

$time_end = microtime(true);
print_r(date("i:s.u", $time_end - $time_start) . '<br>');
print_r($count . ' person');


/*stream reading parse xml file, get all fullname with their corresponding pids
 *@ GetArrayPersonsName
 *@ param:  $path{file path of XML}, $cound{ref param for count the total unique person}
 *@ return: Array[{name:[pid]}]
*/
function GetArrayPersonsName($path, &$count)
{
    $nameArr = array();
    // stream reading
    $xml = new XMLReader();
    $xml->open($path, 'UTF-8');
    while ($xml->read()) {
        // retrieve every <person>
        while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'a2a:Person') {
            $count++;
            // get attribute of pid
            $pid = str_replace('Person:', '', $xml->getAttribute('pid'));
            // regular express for removing 'a2a:' with all string content between(include) <record> and </record>
            $xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
            // covert the string into dom object
            $node = simplexml_load_string($xmlStr);
            // get all textcontent inside <PersonName> into string
            $name = dom_import_simplexml($node->PersonName)->textContent;

            // set full name as key, pid as value, if name not in array, set {name:[pid]}, if in array and pid is new, append pid on value
            if (array_key_exists($name, $nameArr)) {
                if (!in_array($pid, $nameArr[$name])) {
                    array_push($nameArr[$name], $pid);
                }
            } else {
                $nameArr[$name] = array($pid);
            }
            // release memory
            unset($pid);
            unset($name);
            unset($node);
            unset($xmlStr);
            // jump to next
            $xml->moveToNextAttribute();
        }
    }
    $xml->close();
    return $nameArr;
}

/*sort array of key-value according to length of value descending 
 *@ DescSortByLength
 *@ param:  $nameAerr{ Array[{name:[pid]}] which parsed from xml }
 *@ return: Array[{name:[pid]}]
*/
function DescSortByLength($nameArr)
{
    // [{'name':count},...]
    $lengths = array_map('count', $nameArr);
    // descending sort according to value
    arsort($lengths);
    $output = array();
    foreach (array_keys($lengths) as $k)
        $output[$k] = $nameArr[$k];
    return $output;
}

/* top N keys from (sorted)$nameArr
 *@ PopularNameTopN
 *@ param:  $nameAerr{ Array[{name:[pid]}] }, $n {top n ranking}
 *@ return: Array[name]
*/
function PopularNameTopN($nameArr, $n)
{
    $topN = array_slice($nameArr, 0, $n);
    return array_keys($topN);
}
