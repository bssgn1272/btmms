<?php
include "/home/daemon30000/Documents/Access_control/index.php";
//$data =json_decode(file_get_contents("php://input"));

$name = $_REQUEST['name'];
                                $date=$_REQUEST['date'];
$date =time();
$data = new AccesControlModule();
$array = null;
$result= $data->generateBarcodeImage($date);
/**if ($result->num_rows > 0) {

	while($row = $result->fetch_assoc()) {
	$array[] = $row;
	}

}
print_r(json_encode($array));





 */

print_r($result);
//return $result;


?>

