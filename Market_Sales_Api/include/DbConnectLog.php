<?php
/*
Handling database connection
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
            $msg = 'Failed to connect to MySQL: ' .mysqli_connect_error();

            echo $msg;

            //WRITE A MESSAGE TO A FILE IN THE SAME DIRECTORY
            $file = __DIR__ . '/DB_Connection_Error.txt';
            $date = 'Script was executed at ' . date('d/m/Y H:i:s') . "\n" . json_encode($msg) . "\n" . "\n";
            file_put_contents($file, $date, FILE_APPEND);
            //END OF WRITING TO FILE
		}

		//Returning connection resource
		return $this->conn;
	}
}
