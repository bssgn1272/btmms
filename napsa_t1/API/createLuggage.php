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

$array = null;


//$luggage_id = $_REQUEST['luggage_id'];
$fname =  $_REQUEST['fname'];
$lname =  $_REQUEST['lname'];
$description = $_REQUEST['description'];
$weight =  $_REQUEST['weight'];
//$cost = $_REQUEST['cost'];
$reciient_id= $_REQUEST['recipient_id'];
$Destination =$_REQUEST['Destination'];
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
				$id=$data->insertLuggage2($fname,$lname,$description,$weight,$reciient_id,$Destination);
				Header('Location:http://localhost/napsa_t1/receipt.php?luggage_id='.$id);
?>
 
