<?php
require 'Logger.php';
require 'Configs.php';

class Database {
	private $connection;

	public function __construct()
        {
		//access control module control
	$this->logger = new Logger();

    $this->logger->log("about to start the Database class",Configs::INFO);
	
	}

	private function createConnection(){
	
	$servername = "localhost";
$username = "root";
$password = "";
$dbname = "demo";
// Create connection
$conn = mysqli_connect($servername, $username, $password,$dbname);

// Check connection
if (!$conn) {
 $this->logger->log("Database connection failed",Configs::ERROR);
      
	die("Connection failed: " . mysqli_connect_error());
}
 $this->logger->log("connection created successfully",Configs::INFO);

	
return $conn;
	}
  
	public function insertLuggage($description,$weight,$cost, $recipientID,$destination,$name){
	$this->logger->log("Calling the insertLuggage function",Configs::INFO);
	$connection = $this->createConnection();
	$statment = "INSERT INTO Luggage (owner,description,weight,recipientID,cost,Destination) VALUES('$name','$description','$weight','$recipientID','$cost','$destination')";

	$this->logger->log("the statement to run is ".$statment,Configs::INFO);

	if (mysqli_query($connection, $statment)) {
        $this->logger->log("New record created successfully ",Configs::INFO);

	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);


    }


	}




public function insertCustomer($fname,$lname,$phone_number){
	$this->logger->log("Calling the insertLuggage function",Configs::INFO);
	$connection = $this->createConnection();

	$statment = "INSERT INTO customer (fname, lname,phone_number) VALUES ( '$fname', '$lname', '$phone_number')";

	$this->logger->log("the statement to run is ".$statment,Configs::INFO);

	if (mysqli_query($connection, $statment)) {
        $this->logger->log("New record created successfully ID".mysqli_insert_id($connection),Configs::INFO);
        return mysqli_insert_id($connection);

	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);
                return null;

    }


	}

	public function insertLuggage2($fname,$lname,$description,$weight,$cost,$phone_number,$Destination){
	$this->logger->log("Calling the insertLuggage function",Configs::INFO);
	$connection = $this->createConnection();
         $cus_id =$this->insertCustomer($fname,$lname,$phone_number);
	$statment = "INSERT INTO luggage (customer_id, description, weight,Destination ) VALUES ( '$cus_id','$description', '$weight','$Destination')";

	$this->logger->log("the statement to run is ".$statment,Configs::INFO);

	if (mysqli_query($connection, $statment)) {
        $this->logger->log("New record created successfully ID".mysqli_insert_id($connection),Configs::INFO);
        return mysqli_insert_id($connection);

	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);
                return null;

    }


	}




	public function insertPriceRate($PriceRate){
	$this->logger->log("Calling the insertLuggage function",Configs::INFO);
	$connection = $this->createConnection();
	$statment = "INSERT INTO pricerate (id,priceRate) VALUES ( null,'$PriceRate',)";

	$this->logger->log("the statement to run is ".$statment,Configs::INFO);

	if (mysqli_query($connection, $statment)) {
        $this->logger->log("New record created successfully ID".mysqli_insert_id($connection),Configs::INFO);
        return mysqli_insert_id($connection);

	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);
                return null;

    }


	}




 public function getPriceRate(){
        $this->logger->log("Calling the insertLuggage function",Configs::INFO);
        $connection = $this->createConnection();
        $statment = "SELECT * FROM pricerate order by id desc limit 0,1";

        $this->logger->log("the statement to run is ".$statment,Configs::INFO);

	$result =mysqli_query($connection, $statment);
	if($result){
        $this->logger->log("New record created successfully ",Configs::INFO);
  
         return $result;
	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);


    }

       


}


 public function getLuggage(){
        $this->logger->log("Calling the insertLuggage function",Configs::INFO);
        $connection = $this->createConnection();
        $statment = "SELECT * FROM Luggage";

        $this->logger->log("the statement to run is ".$statment,Configs::INFO);

	$result =mysqli_query($connection, $statment);
	if($result){
        $this->logger->log("New record created successfully ",Configs::INFO);
  
         return $result;
	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);


    }

       


}






public function getLuggageByDestination($dest){
        $this->logger->log("Calling the insertLuggage function",Configs::INFO);
        $connection = $this->createConnection();
        $statment = "SELECT * FROM Luggage WHERE Destination ='$dest'";
 
        $this->logger->log("the statement to run is ".$statment,Configs::INFO);

	$result =mysqli_query($connection, $statment);
	if($result){
        $this->logger->log("New record created successfully ",Configs::INFO);
  
         return $result;
	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);


    }



}



public function getLuggageByCustomer($cus){
        $this->logger->log("Calling the insertLuggage function",Configs::INFO);
        $connection = $this->createConnection();
        $statment = "SELECT * FROM Luggage WHERE owner ='$cus'";
 
        $this->logger->log("the statement to run is ".$statment,Configs::INFO);

	$result =mysqli_query($connection, $statment);
	if($result){
        $this->logger->log("New record created successfully ",Configs::INFO);
  
         return $result;
	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);


    }



}



public function getLuggageByID($cus){
        $this->logger->log("Calling the insertLuggage function",Configs::INFO);
        $connection = $this->createConnection();
        $statment = "SELECT * FROM luggage WHERE luggage_id ='$cus'";
 
        $this->logger->log("the statement to run is ".$statment,Configs::INFO);

	$result =mysqli_query($connection, $statment);
	if($result){
        $this->logger->log("New record created successfully ",Configs::INFO);
  
         return $result;
	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);


    }
}



public function getCustomerByID($cus){
        $this->logger->log("Calling the insertLuggage function",Configs::INFO);
        $connection = $this->createConnection();
        $statment = "SELECT * FROM customer_id WHERE _id ='$cus'";
 
        $this->logger->log("the statement to run is ".$statment,Configs::INFO);

	$result =mysqli_query($connection, $statment);
	if($result){
        $this->logger->log("New record created successfully ",Configs::INFO);
  
         return $result;
	} else {
        $this->logger->log("Error".mysqli_error($connection),Configs::ERROR);


    }


}





}

?>
