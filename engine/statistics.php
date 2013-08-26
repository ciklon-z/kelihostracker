<?php
date_default_timezone_set('Australia/Hobart');
error_reporting(0);
$cmd = $_GET["request"];
switch ($cmd) {
    case "stats":
		$updated = "21-08-13";
		$dData = explode("\n",file_get_contents('db/full.db'));
		foreach ($dData as $data)
		{
		$value = $str = str_replace("\n", '', $data);
		$dASN = explode("|",$data);
		$ASN = $dASN[2];
		$cnStats[] = $ASN;
		}
		$totalIP = count($cnStats);
		$array = array_count_values($cnStats);
		asort($array);
		$cnStats = array_slice($array, -1, 1, true);
	    //Shouldn't have to do this
		foreach ($cnStats as $ASN => $total)
		{
		$worstCN = $ASN;
		}
		$activeF = 'db/active.db';
		$suspendedF = 'db/suspended.db';
		$active = count(file($activeF));
		$suspended = count(file($suspendedF));
		$total = $active + $suspended;
		$jSON = array('dIP' => $totalIP, 'tDomains' => $total, 'dSuspended' => $suspended, 'wASN' => $worstCN, 'updated' => $updated);
		echo json_encode($jSON);
        break;
    case "statsCN":
		$dData = explode("\n",file_get_contents('db/full.db'));
		foreach ($dData as $data)
		{
		$value = $str = str_replace("\n", '', $data);
		$dASN = explode("|",$data);
		$CN = $dASN[4];
		$cnStats[] = $CN;
		}
		$jSON[] = array('CN', 'Total');
		$array = array_count_values($cnStats);
		asort($array);
		$cnStats = array_slice($array, -10, 10, true);
		foreach ($cnStats as $CN => $total)
		{
		$jSON[] = array($CN, $total);
		}
		echo json_encode($jSON);
        break;
    case "statsAS":
		$dData = explode("\n",file_get_contents('db/full.db'));
		foreach ($dData as $data)
		{
		$value = $str = str_replace("\n", '', $data);
		$dASN = explode("|",$data);
		$CN = $dASN[2];
		$cnStats[] = $CN;
		}
		$jSON[] = array('ASN', 'Total');
		$array = array_count_values($cnStats);
		asort($array);
		$cnStats = array_slice($array, -10, 10, true);
		foreach ($cnStats as $CN => $total)
		{
		$jSON[] = array("$CN", (int) $total);
		}
		echo json_encode($jSON);
        break;
	case "dActive":
		$fileData = explode("\n",file_get_contents('db/active.db'));
		foreach ($fileData as $value)
		{
		$dData = explode("|",$value);
		$domain = $dData[0];
		$reg = $dData[1];
		$ns = $dData[2];
		$date = $dData[3];
		if($reg != null) {
		$jSON[] = array($domain, $reg, $ns, $date);
		}
		}
		echo json_encode($jSON);
        break;
	case "dSuspended":
		$fileData = explode("\n",file_get_contents('db/suspended.db'));
		foreach ($fileData as $value)
		{
		$dData = explode("|",$value);
		$domain = $dData[0];
		$reg = $dData[1];
		$date = $dData[2];
		if($reg != null) {
		$jSON[] = array($domain, $reg, $date);
		}
		}
		echo json_encode($jSON);
        break;
	case "iFull":
		$fileData = explode("\n",file_get_contents('db/full.db'));
		foreach ($fileData as $value)
		{
		$dData = explode("|",$value);
		if($dData[0] != null) {
		$jSON[] = array($dData[0], $dData[1], $dData[2], $dData[3], $dData[4], $dData[5]);
		}
		}
		echo json_encode($jSON);
        break;
	case "iDaily":
		$fileData = explode("\n",file_get_contents('db/full.db'));
		foreach ($fileData as $value)
		{
		$dData = explode("|",$value);
		//$today = date("F j, Y");
		$today = date("j M Y");
		if (strpos($dData[5],$today) !== false) {
		$jSON[] = array($dData[0], $dData[1], $dData[2], $dData[3], $dData[4], $dData[5]);
		}
		}
		echo json_encode($jSON);
        break;
}
?>
