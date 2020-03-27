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
$fname =  trim($_REQUEST['fname']);
$lname =  trim($_REQUEST['lname']);
$description = trim($_REQUEST['description']);
$weight =  trim($_REQUEST['weight']);
//$cost = $_REQUEST['cost'];
$reciient_id= trim($_REQUEST['recipient_id']);
$Destination =trim($_REQUEST['Destination']);

if(empty($fname)){
$error = "please enter the first name ";
	Header('Location:../Luggage_ui.php?error='.$error);

}else if(empty($lname)){
 $error = "please enter last name ";
	Header('Location:../Luggage_ui.php?error='.$error);

}else if(empty($description)){

$error = "please enter the description ";
	Header('Location:../Luggage_ui.php?error='.$error);

}else if(empty($weight)){

$error = "please enter the weight ";
	Header('Location:../Luggage_ui.php?error='.$error);


}else if(empty($reciient_id)){

$error = "please enter the Recipient";
	Header('Location:../Luggage_ui.php?error='.$error);


}else if(empty($Destination)){
$error = "please enter the Destination ";
	Header('Location:../Luggage_ui.php?error='.$error);

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
				$id=$data->insertLuggage2($fname,$lname,$description,$weight,$reciient_id,$Destination);
				Header('Location:../receipt.php?luggage_id='.$id);
}else{

$error = "please enter data ";
	Header('Location:../Luggage_ui.php?error='.$error);

}
?>
 
