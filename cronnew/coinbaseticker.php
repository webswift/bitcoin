<?php 
include_once 'config/db.php';
include_once "library/ccxt.php";
// error_reporting(E_ALL);

// date_default_timezone_set ('UTC');

$sql_row_update_coinbase= [];

$allexpair = $DB->query("SELECT id,pair,exchange_name FROM pairnew WHERE exchange_name='coinbase'");

$exchange = new \ccxt\coinbase(); // default id

foreach ($allexpair as $expair) {

	$fetchticker = $exchange->fetchTicker($expair['pair']);

	$sql = " UPDATE pairnew ";
	$sql .= " SET close=\"" . (float)$fetchticker["last"] . "\" ";
	$sql .= ", update_date = \"". date('Y-m-d H:i:s') ."\" ";
	$sql .= " WHERE ";
	$sql .= " id=" . $expair["id"] . " ;";		
	if($DB->query($sql) === TRUE) {
		$sql_row_update_coinbase[] = $expair["pair"];
	} else {
		$sql_row_update_coinbase[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
	}				
}

echo "<pre>"; print_r('========= COINBASE ROW UPDATE =========='); echo "</pre>"; 
echo "<pre>"; print_r($sql_row_update_coinbase); echo "</pre>"; 

$DB->close();
?>