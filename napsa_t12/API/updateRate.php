<?php
error_reporting(E_ERROR | E_PARSE);

require "database.php";

//$data =json_decode(file_get_contents("php://input"));
/**
$name = $_POST['name'];
				$description=$_POST['luggage'];
				$cost=$_POST['cost'];
				$weight=$_POST['weight'];
				$recipientID=$_POST['recipientID'];
				$destination = $_POST['destination'];




*/
if(isset($_REQUEST['submit'])){

$array = null;


//$luggage_id = $_REQUEST['luggage_id'];
$rate =  trim($_REQUEST['rate']);


if(empty($rate)){
$error = "please enter the first name ";
	Header('Location:../ViewRate.php?error='.$error);

}


 				$data = new Database();
/**$result= $data->getPriceRate();
if ($result->num_rows > 0) {

        while($row = $result->fetch_assoc()) {
        $array[] = $row;
        }

}
$priceRate =$array[0]['priceRate'];
$cost = $weight*$priceRate;
*/
				//return $data->insertLuggage($description,$weight,$cost, $recipientID,$destination,$name);
				$id=$data->updateRate($rate);
				Header('Location:../ViewRate.php');
}else{

$error = "please enter data ";
	Header('Location:../ViewRate.php');

}
?>
 
