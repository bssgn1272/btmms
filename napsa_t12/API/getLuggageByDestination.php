<?php
include "/home/daemon30000/Documents/Access_control/database.php";
//$data =json_decode(file_get_contents("php://input"));



$data = new Database();
$array = null;
$dest='lusaka';
$result= $data->getLuggageByDestination($dest);
if ($result->num_rows > 0) {

	while($row = $result->fetch_assoc()) {
	$array[] = $row;
	}

}
	print_r(json_encode($array));
?>

