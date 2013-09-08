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
	$fileData = explode("\n",file_get_contents($hostFile));
foreach ($fileData as $value)
{
	$dData = explode("|",$value);
	$domain = $dData[0];
	$infectedHosts[] = getNameservers("$domain");
} 
	return array_filter($infectedHosts);
}

function checkURLQueryHosts($queryURL) {
// enable user error handling
	libxml_use_internal_errors(true);
	$doc = new DOMDocument();
	@$doc->loadHTMLFile($queryURL);
	$errors = libxml_get_errors();
foreach ($errors as $error) {
if($error->code == "1549") {
	echo "Failed to get urlquery.net\n";
	exit();
}
}
	$urls = $doc->getElementsByTagName('a');
foreach ($urls as $url) {
	$attributes = $url->attributes;
	$data = parse_url($url->nodeValue);
	$hosts[] = strtoupper($data[host]);
}

	$host_list = array_unique(array_filter($hosts));
foreach ($host_list as $host) {
	$infectedHosts[] = getNameservers("$host");
}
	return array_filter($infectedHosts);
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

function MoveSuspended($Old,$New,$SuspendFile) {
	$fileData = explode("\n",file_get_contents('db/active.db'));
	$newdomains = array_merge($Old,$New);
foreach ($fileData as $value)
{
	$dData = explode("|",$value);
	$domain = $dData[0];
	$reg = $dData[0];
	if (!in_array($domain, $newdomains)) {
	$fh = fopen($SuspendFile, 'a') or die("can't open file");
	fwrite($fh, "$domain|$reg\n");
	fclose($fh);
	}

} 


}

//Main
$activeFile = 'db/active.db';
$suspendedFile = "db/suspended.db";
$NewHosts = checkURLQueryHosts('http://urlquery.net/search.php?q=?id=kh');
$OldHosts = checkCurrentHosts($activeFile);
MoveSuspended($NewHosts,$OldHosts,$suspendedFile);
$f = @fopen($activeFile, "r+");
if ($f !== false) {
    ftruncate($f, 0);
    fclose($f);
}
if (is_array($NewHosts)) {
foreach ($NewHosts as $host) {
$myFile = $activeFile;
$fh = fopen($myFile, 'a') or die("can't open file");
fwrite($fh, "$host\n");
fclose($fh);
}
} elseif (is_array($OldHosts)) {
foreach ($OldHosts as $host) {
$myFile = $activeFile;
$fh = fopen($myFile, 'a') or die("can't open file");
fwrite($fh, "$host\n");
fclose($fh);
}
}
?>
