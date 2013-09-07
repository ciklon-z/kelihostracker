<?
require_once 'Net/DNS2.php';
//Setup vars
$ns = array('8.8.8.8', '8.8.4.4');

function checkServerheaders($ip,$host) {
$header = explode("\n",downloadURL($ip));
if(trim(preg_replace('/\s\s+/', ' ', $header[1])) == "Server: Apache" && trim(preg_replace('/\s\s+/', ' ', $header[6])) ==  "Server:nginx/1.2.6") {
return "$host";
}
}




function checkNameservers($ns,$host) {
$nameservers = array('8.8.8.8', '8.8.4.4');
$type = "NS";
$rs = new Net_DNS2_Resolver(array('nameservers' => $nameservers));
if (strpos($ns,strtolower($host)) !== false) {
try {
$result = $rs->query("$ns", "A");
} catch(Net_DNS2_Exception  $e) {}
//Output NS A record data to $nsData if $host is detected in $ns
foreach ($result->answer as $nsData)
{
return checkServerheaders("{$nsData->address}",$host);
}

}
}


function getNameservers($host) {
$ns = array('8.8.8.8', '8.8.4.4');
$type = "NS";
$rs = new Net_DNS2_Resolver(array('nameservers' => $ns));
try {
//Run query on host for NS type
$result = $rs->query($host, $type);
} catch(Net_DNS2_Exception  $e) {}
if (is_array($result->answer))
{
foreach ($result->answer as $record)
{
return checkNameservers("{$record->nsdname}",$host);
}
}
}

function checkCurrentHosts($hostFile) {

}

function checkURLQueryHosts($queryURL) {
$doc = new DOMDocument();
@$doc->loadHTMLFile($queryURL);
$urls = $doc->getElementsByTagName('a');
foreach ($urls as $url) {
	$attributes = $url->attributes;
	$data = parse_url($url->nodeValue);
	$hosts[] = strtoupper($data[host]);
}

$host_list = array_unique(array_filter($hosts));
print_r($host_list);
foreach ($host_list as $host) {
return getNameservers("$host");
}

}



function downloadURL($URL) {
if(!function_exists('curl_init')) {
 die ("Curl PHP package not installedn");
}
$curlHandle = curl_init();
curl_setopt($curlHandle, CURLOPT_URL, $URL);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curlHandle, CURLOPT_TIMEOUT, 5);
curl_setopt($curlHandle, CURLOPT_HEADER, true);
$response = curl_exec($curlHandle);
return $response;
}






//Main Program
checkURLQueryHosts('http://urlquery.net/search.php?q=?id=kh');





?>
