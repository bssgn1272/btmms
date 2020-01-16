<?php
include "database.php";
//$data =json_decode(file_get_contents("php://input"));



$data = new Database();
$array = null;
$result= $data->getPriceRate();
if ($result->num_rows > 0) {

	while($row = $result->fetch_assoc()) {
	$array[] = $row;
	}

}
$priceRate =$array[0]['priceRate'];

	print_r($priceRate);
?>

