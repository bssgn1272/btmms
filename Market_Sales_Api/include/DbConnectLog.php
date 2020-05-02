<?php
/*
Handling database connection
*/

/**
* 
*/
class DbConnectLog {

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
		$this->conn = new mysqli(DB_HOST_LOG,DB_USERNAME_LOG,DB_PASSWORD_LOG,DB_NAME_LOG);


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