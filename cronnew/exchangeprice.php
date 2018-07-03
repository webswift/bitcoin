<?php 

include_once 'config/db.php';

$coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=USDT&e=binance');
$coinsprice = json_decode($coinsprice,true);
$sql_row_update = [];

$sql = " UPDATE exchange ";
$sql .= " SET price=\"" . (float)$coinsprice["RAW"]['BTC']['USDT']['PRICE'] . "\" ";
$sql .= ", volume = \"". (float)$coinsprice["RAW"]['BTC']['USDT']['VOLUME24HOURTO'] ."\" ";
$sql .= ", 24h = \"". (float)$coinsprice["RAW"]['BTC']['USDT']['CHANGEPCT24HOUR'] ."\" ";
$sql .= ", update_date = \"". date('Y-m-d H:i:s') ."\" ";
$sql .= " WHERE ";
$sql .= " id=1;";		
if($DB->query($sql) === TRUE) {
	$sql_row_update[] = 'binance update';
} else {
	$sql_row_update[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
}

$coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=USD&e=kraken');
$coinsprice = json_decode($coinsprice,true);

$sql = " UPDATE exchange ";
$sql .= " SET price=\"" . (float)$coinsprice["RAW"]['BTC']['USD']['PRICE'] . "\" ";
$sql .= ", volume = \"". (float)$coinsprice["RAW"]['BTC']['USD']['VOLUME24HOURTO'] ."\" ";
$sql .= ", 24h = \"". (float)$coinsprice["RAW"]['BTC']['USD']['CHANGEPCT24HOUR'] ."\" ";
$sql .= ", update_date = \"". date('Y-m-d H:i:s') ."\" ";
$sql .= " WHERE ";
$sql .= " id=4;";		
if($DB->query($sql) === TRUE) {
	$sql_row_update[] = 'kraken update';
} else {
	$sql_row_update[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
}

$coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=USD&e=coinbase');
$coinsprice = json_decode($coinsprice,true);

$sql = " UPDATE exchange ";
$sql .= " SET price=\"" . (float)$coinsprice["RAW"]['BTC']['USD']['PRICE'] . "\" ";
// $sql .= ", volume = \"". (float)$coinsprice["RAW"]['BTC']['USD']['VOLUME24HOURTO'] ."\" ";
// $sql .= ", 24h = \"". (float)$coinsprice["RAW"]['BTC']['USD']['CHANGEPCT24HOUR'] ."\" ";
$sql .= ", update_date = \"". date('Y-m-d H:i:s') ."\" ";
$sql .= " WHERE ";
$sql .= " id=3;";		
if($DB->query($sql) === TRUE) {
	$sql_row_update[] = 'coinbase update';
} else {
	$sql_row_update[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
}
$sql = " UPDATE pair ";
$sql .= " SET price=\"" . (float)$coinsprice["RAW"]['BTC']['USD']['PRICE'] . "\" ";
$sql .= ", update_date = \"". date('Y-m-d H:i:s') ."\" ";
$sql .= " WHERE ";
$sql .= " id=139";
if($DB->query($sql) === TRUE) {
	$sql_row_update[] = 'coinbase pair update';
} else {
	$sql_row_update[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
}

$coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC&tsyms=KRW&e=bithumb');
$coinsprice = json_decode($coinsprice,true);

$sql = " UPDATE exchange ";
$sql .= " SET price=\"" . (float)$coinsprice["RAW"]['BTC']['KRW']['PRICE']*0.00090 . "\" ";
$sql .= ", volume = \"". (float)$coinsprice["RAW"]['BTC']['KRW']['VOLUME24HOURTO']*0.00090 ."\" ";
$sql .= ", 24h = \"". (float)$coinsprice["RAW"]['BTC']['KRW']['CHANGEPCT24HOUR'] ."\" ";
$sql .= ", update_date = \"". date('Y-m-d H:i:s') ."\" ";
$sql .= " WHERE ";
$sql .= " id=2;";		
if($DB->query($sql) === TRUE) {
	$sql_row_update[] = 'bithumb update';
} else {
	$sql_row_update[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
}	

echo "<pre>"; print_r('========= ROW UPDATE =========='); echo "</pre>"; 
echo "<pre>"; print_r($sql_row_update); echo "</pre>"; 		

$DB->close();
?>