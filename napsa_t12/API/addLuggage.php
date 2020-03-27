
<?php
			session_start();


require "database.php";

			/**if (!isset($_SESSION['loggedin'])) {
				header('Location: login.php');
				exit();
			}*/





if(isset($_REQUEST['submit'])){

$i = 0;


$data = new Database();
$array = null;
$result= $data->getPriceRate();
if ($result->num_rows > 0) {

	while($row = $result->fetch_assoc()) {
	$array[] = $row;
	}

}
$priceRate =$array[0]['rate'];




$array = null;


//$luggage_id = $_REQUEST['luggage_id'];
$fname =  trim($_REQUEST['fname']);
$lname =  trim($_REQUEST['lname']);
$description = trim($_REQUEST['description']);
$weight =  trim($_REQUEST['weight']);
//$cost = $_REQUEST['cost'];
$reciient_id= trim($_REQUEST['recipient_id']);
$Destination =trim($_REQUEST['Destination']);
$cost = $priceRate * $weight;



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

$itemArray = array(array('fname'=>$fname,'lname'=>$lname, 'description'=>$description, 'weight'=>$weight,'recipient_id'=>$reciient_id,'Destination'=>$Destination ,'cost'=>$cost));
		
if(empty($_SESSION['cart'])){
$_SESSION['cart'] = $itemArray;
}else{

$_SESSION["cart"] = array_merge($_SESSION["cart"],$itemArray);

}
$totalCost = 0;


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
<link rel="stylesheet" href="../luggage.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="../css/style.css">

    
</head>


<body>

  

   <div class="content">

	 <!-- <p>Welcome, <?=$_SESSION['name']?>!</p> -->


    <h2>Weight</h2>
      <!-- table -->
      <table class="table table-dark">
     <thead>
     <tr class="bg-warning">
       <th>Description</th>
       <th>Weight</th>
       <th>Cost</th>
       
    </tr>
    </thead>

  </div>

  <?php
     


foreach ($_SESSION["cart"] as $item){

$totalCost += $item['cost'];
$totalweight += $item['weight'];
$dest = $item["Destination"];


         // output data of each row
        
           echo "<tr>";
              //echo "<td>"  . $row["bus_id"] .  "</td>";
             // echo "<td>"  . $row["time"].  "</td>";
              echo "<td>"  . $item["description"] .  "</td>";
             // echo "<td>"  . $item["Destination"] .  "</td>";
							echo "<td>"  .$item["weight"] .  "</td>";
							echo "<td>"  . $item["cost"] .  "</td>";
           echo "</tr>";
         }
echo "<tr>";
echo "<td> TOTAL Weight :"  .$totalweight .  "</td>";
echo "<td> TOTAL COST :"  .$totalCost .  "</td>";
echo "</tr>";
         echo "</table>";
       

    
?>

<br>


<?php				
}else{

$error = "please enter data ";
	Header('Location:../Luggage_ui.php?error='.$error);

}
?>

<form action="../Luggage_ui.php" method="get">
    <input type="submit" value="Weigh more " 
         name="Submit" id="frm1_submit" class="contact100-form-btn"/>
</form>





<form name = "form1" action="createLuggage.php" method = "post"  >
      
 <input  type = "hidden" name = "fname" value = <?php echo $item["fname"];?>/>
 <input  type = "hidden" name = "lname" value = "<?php echo $item["lname"];?>"/>
 <input  TYPE="hidden" name="description" value="<?php echo $item["description"];?>" />
 <input  class="input100" type = "hidden" name = "weight" value = "<?php echo"$totalweight";?>"/>
<input type = "hidden" name ="recipient_id" value = "<?php echo $item["recipient_id"];?>" 
                            />
 <input type ="hidden" name = "Destination" value ="<?php echo $dest;?>"/>
                          

                          <button type="submit" name="submit" class="contact100-form-btn" value="Submit">Generate Receipt</button>

                     
        </form>

  <!-- Footer -->
  <div class="footer">
     <p> All rights reserved. © 2019 Copyright:
       <a href="https://www.napsa.co.zm/">napsa.co.zm</a>
     </p>
  </div>


</body>
</html>
