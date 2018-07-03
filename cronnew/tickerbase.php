<?php 

include_once 'config/db.php';
$allbase = $DB->query("SELECT group_concat(distinct(to_coin)) as base FROM `pairnew`");
$row  = mysqli_fetch_assoc($allbase);
$sql_row_update = [];
if($row['base']){
	$coinsprice = file_get_contents('https://min-api.cryptocompare.com/data/pricemulti?fsyms='.$row['base'].'&tsyms=USD');
	
	
	$coinsprice = json_decode($coinsprice,true);
	$coinsprice['KRW']['USD'] = '0.00089';
	$coinsprice['JPY']['USD'] = '0.0090';
	


	$all = $DB->query("SELECT distinct(to_coin) as to_coin FROM `pairnew`");
	foreach ($all as $r) {
		$sql = " UPDATE pairnew ";
		$sql .= " SET base_usd=\"" . (float)$coinsprice[$r["to_coin"]]['USD'] . "\" ";
		$sql .= " WHERE ";
		$sql .= " to_coin='" . $r["to_coin"] . "' ;";		
		if($DB->query($sql) === TRUE) {
			$sql_row_update[] = $r["to_coin"].'=>'.$coinsprice[$r["to_coin"]]['USD'];
		} else {
			$sql_row_update[] = "Error: " . $sql . "<br>" . $DB->error."<br>";
		}				
	}
}
	

echo "<pre>"; print_r('========= ROW UPDATE =========='); echo "</pre>"; 
echo "<pre>"; print_r($sql_row_update); echo "</pre>";

$DB->close();
?>