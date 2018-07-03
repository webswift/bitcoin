<?php 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);


include_once 'config/db.php';


include_once "library/ccxt.php";

$sql_row_update_binance	= [];

$allexpair = $DB->query("SELECT id,pair,exchange_name FROM pairnew WHERE exchange_name='binance'");



//print_r($allexpair);exit;

$exchange = new \ccxt\binance(); // default id


foreach ($allexpair as $expair) {

	$fetchticker = $exchange->fetchTicker($expair['pair']);

	$sql = " UPDATE pairnew ";
	$sql .= " SET high=\"" . (float)$fetchticker["high"] . "\" ";
	$sql .= ", low = \"". (float)$fetchticker['low'] ."\" ";
	$sql .= ", open = \"". (float)$fetchticker['open'] ."\" ";
	$sql .= ", close = \"". (float)$fetchticker['close'] ."\" ";
	$sql .= ", basevolume = \"". (int)$fetchticker['baseVolume'] ."\" "; 
	$sql .= ", quotevolume = \"". (float)$fetchticker['quoteVolume'] ."\" ";
	$sql .= ", update_date = \"". date('Y-m-d H:i:s') ."\" ";
	$sql .= " WHERE ";
	$sql .= " id=" . $expair["id"] . " ;";		
	if($DB->query($sql) === TRUE) {
		$sql_row_update_binance[] = $expair["pair"];
	} else {
		$sql_row_update_binance[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
	}			
}

echo "<pre>"; print_r('========= BINANCE ROW UPDATE =========='); echo "</pre>"; 
echo "<pre>"; print_r($sql_row_update_binance); echo "</pre>"; 

$DB->close();
?>