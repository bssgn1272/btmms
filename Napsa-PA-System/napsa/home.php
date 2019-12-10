<?php 
session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit();
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Home Page</title>
		     <style>
      .footer {
         position: fixed;
         left: 0;
         bottom: 0;
         width: 100%;
         background-color: gray;
         color: black;
         text-align: center;
          }
    </style>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
         integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
         crossorigin="anonymous">
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
         integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
         crossorigin="anonymous">
    </script>
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>PA System </h1>
				<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Bus Schedule</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
			  <!-- table -->
      <table class="table table-dark">
        <thead>
          <tr class="bg-warning">
            <th>ID</th>
            <th>Company</th>
            <th>Type</th>
            <th>Seats</th>
            <th>Bay</th>
            <th>Time</th>
          </tr>
        </thead>
		</div>
		<?php


 $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "napsadb";

 $con = new mysqli($servername, $username, $password, $dbname);
        // Check connection to database
        if ($con->connect_error) {
            die("Connection failed: " . $con->connect_error);
        }

$sql = "SELECT id, company, type, seats, Bay, Time FROM bus";
        $result =$con ->query($sql);

        if ($result->num_rows > 0) {
           // output data of each row
           while($row = $result->fetch_assoc()) {
             echo "<tr>";
                echo "<td>"  . $row["id"] .  "</td>";
                echo "<td>"  . $row["company"].  "</td>";
                echo "<td>"  . $row["type"] .  "</td>";
                echo "<td>"  . $row["seats"] .  "</td>";
                echo "<td>"  . $row["Bay"] .  "</td>";
                echo "<td>"  . $row["Time"] .  "</td>";
             echo "</tr>";
           }
           echo "</table>";
        } else {
           echo "0 results";
        }

       $con ->close();

?>
		
		 <!-- footer -->
    <div class="footer">
      <p>© 2019 Copyright:
        <a href="https://www.napsa.co.zm/">napsa.co.zm</a>
      </p>
    </div>
	</body>
</html>