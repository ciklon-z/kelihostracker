<?php
function downloadURL($URL) {
 if(!function_exists('curl_init')) {
 die ("Curl PHP package not installedn");
 }
 /*Initializing CURL*/
 $curlHandle = curl_init();
 /*The URL to be downloaded is set*/
 curl_setopt($curlHandle, CURLOPT_URL, $URL);
 /*Return the HTTP headers*/
 //curl_setopt($curlHandle, CURLOPT_VERBOSE, 1);
curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($curlHandle, CURLOPT_TIMEOUT, 5); // in seconds
 curl_setopt($curlHandle, CURLOPT_HEADER, true);
 /*Now execute the CURL, download the URL specified*/
 $response = curl_exec($curlHandle);
 return $response;
}
//Hostname


function CheckDomain($host) {
//Use Google DNS
        $ns = array('8.8.8.8', '8.8.4.4');
        $type = "NS";
	require_once 'Net/DNS2.php';
        $rs = new Net_DNS2_Resolver(array('nameservers' => $ns));
try {
	//Run query on host for NS type
	$result = $rs->query($host, $type);
} catch(Net_DNS2_Exception  $e) {
        echo "Failed to query: " . $e->getMessage() . "\n";
}
	//Loop through NS detected on host
foreach($result->answer as $record){
	//Add NS to nsArray
	$nsArray[] = "{$record->nsdname}";
}
	//Loop though name servers and run A record check | IP
foreach($nsArray as $NS){
if (strpos($NS,strtolower($host)) !== false) {
	try {
		$result = $rs->query("$NS", "A");
	} catch(Net_DNS2_Exception  $e) {
		echo "Failed to query: " . $e->getMessage() . "\n";
	}
	//Output NS A record data to $nsData
	$nsData = $result->answer;
	$ipData[] = array("ip"=>$nsData[0]->address,"name"=>$nsData[0]->name,"ttl"=>$nsData[0]->ttl);
} else {
echo "Nameservers do not look like Kelihos\n";
}
}
foreach($ipData as $ip=>$key){+
	$header = downloadURL($key["ip"]);
	$response = explode("\n",$header);
	$hData[] = array($key["name"],$key["ip"],$response[0],$response[1], $response[6]);
}

//Check header data for Double server
foreach($hData as $Hosts){
$Hosts[4] = trim(preg_replace('/\s\s+/', ' ', $Hosts[4]));
$Hosts[3] = trim(preg_replace('/\s\s+/', ' ', $Hosts[3]));
if($Hosts[3] == "Server: Apache" && $Hosts[4] ==  "Server:nginx/1.2.6") {
$final_result[] =  "kelihos";
}
}
if (in_array("kelihos", $final_result)) {
    echo "Domain $host - Kelihos Detected\n";
	$myFile = "active.db";
	$fh = fopen($myFile, 'a') or die("can't open file");
	fwrite($fh, "$host\n");
	fclose($fh);
} else {
    echo "Domain $host - Kelihos NOT Detected\n";
}
}



$fileData = explode("\n",file_get_contents('active.db'));
foreach ($fileData as $value)
{
	$dData = explode("|",$value);
	$domain = $dData[0];
	CheckDomain($domain,"first");
} 

//Load urlquery data checking for domains with ?id=kh sort into $host_list array 
$doc = new DOMDocument();
@$doc->loadHTMLFile('http://urlquery.net/search.php?q=?id=kh');
$urls = $doc->getElementsByTagName('a');
foreach ($urls as $url) {
	$attributes = $url->attributes;
	$data = parse_url($url->nodeValue);
	$hosts[] = strtoupper($data[host]);
}
	$host_list = array_unique(array_filter($hosts));
foreach ($host_list as $host) {
	CheckDomain($host);
}

?>

