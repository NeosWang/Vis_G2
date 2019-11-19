<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];

// initialize redis clinet
require $rootPath . "/lib/predis/autoload.php";
$redis = new Predis\Client();

$time_start = microtime(true);

// path of target file
$path = $rootPath . "/data/xml/bhic_a2a_bs_o-201911.xml";

$count = XmlParser($path, $redis);

$time_end = microtime(true);

print_r(date("i:s.u", $time_end - $time_start) . '<br>');
print_r($count . ' records');


/*stream reading parse xml file, encdoe to json then store into redis with key:value
 *@ XmlParser
 *@ param:  $path{file path of XML}, $redis{redis client}
 *@ return: int{total number of records}
*/
function XmlParser($path, $redis)
{
	$count = 0;
	// stream reading
	$xml = new XMLReader();
	$xml->open($path, 'UTF-8');
	while ($xml->read()) {
		// retrieve every <record>
		while ($xml->nodeType == XMLReader::ELEMENT && $xml->name == 'record') {
			$count++;
			// regular express for removing 'a2a:' with all string content between(include) <record> and </record>
			$xmlStr = preg_replace('~(</?|\s)(a2a):~is', '$1', $xml->readOuterXML());
			// covert the string into dom object
			$node = simplexml_load_string($xmlStr);
			// set identifier as key and all content(tag/attribute/text) encode to json as value
			// store into redis
			$redis->set($node->header->identifier, json_encode($node));
			// release memory for ref type variables
			unset($node);
			unset($xmlStr);
			// jump to next <record>
			$xml->next('record');
		}
	}
	$xml->close();
	return $count;
}
