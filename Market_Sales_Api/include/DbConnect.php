<?php
/*
Handling database connection
*/

/**
* 
*/
class DbConnect {

	private $conn;
	
	function __construct()
	{
		# code...
	}

	//Establishing database connection
	//@return database connection handler
	function connect(){

        include_once  'Config.php';

		//Connecting to mysql database
		$this->conn = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_NAME);


		//Check for database connection error
		if (mysqli_connect_errno()) {
			# code...
			echo 'Failed to connect to MySQL: ' .mysqli_connect_error();
		}

		//Returning connection resource
		return $this->conn;
	}
}
?>