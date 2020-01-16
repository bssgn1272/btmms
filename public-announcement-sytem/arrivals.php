
<?php
			session_start();

			if (!isset($_SESSION['loggedin'])) {
				header('Location: login.php');
				exit();
			}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="css/style.css">

    <title>Public Announcement System</title>

</head>


<body>

<!-- Main Page content -->
   <div class="content">

	 <!-- <p>Welcome, <?=$_SESSION['name']?>!</p> -->


    <h2>ARRIVALS</h2>
      <!-- table -->
      <table class="table table-dark">
     <thead>
     <tr class="bg-warning">
       <th>Bus Number</th>
       <th>Time</th>
       <th>From</th>
       <th>Company</th>
			 <th>Bay</th>
			 <th>Status</th>
    </tr>
    </thead>

  </div>

  <?php
      $servername = "localhost";
      $username = "mainuser";
      $password = "";
      $dbname = "prototype";

$con = new mysqli($servername, $username, $password, $dbname);
      // Check connection to database
      if ($con->connect_error) {
          die("Connection failed: " . $con->connect_error);
      }

// INNER JOIN query to pull data from parent and child tables
$sql = "SELECT
					         bus_number,
									 time,
									 route_destination,
									 company_name,
									 bay_name,
									 status_message
						FROM
						        Arrivals AS b
						INNER JOIN Company AS c
						          ON b.company_id = c.company_id
            INNER JOIN Route AS r
					            ON b.route_id = r.route_id
						INNER JOIN Bay as k
						          ON b.bay_id = k.bay_id
						INNER JOIN Status as s
						          ON b.status_id = s.status_id
											ORDER BY time";



$result =$con ->query($sql);

      if ($result->num_rows > 0) {
         // output data of each row
         while($row = $result->fetch_assoc()) {
           echo "<tr>";
              echo "<td>"  . $row["bus_number"] .  "</td>";
              echo "<td>"  . $row["time"].  "</td>";
              echo "<td>"  . $row["route_destination"] .  "</td>";
              echo "<td>"  . $row["company_name"] .  "</td>";
							echo "<td>"  . $row["bay_name"] .  "</td>";
							echo "<td>"  . $row["status_message"] .  "</td>";
           echo "</tr>";
         }
         echo "</table>";
       }

     $con ->close();
?>

<!-- Bus schedule toggle button -->
<a class="btn btn-primary" href="departures.html" role="button">Departures</a>

<br>
  <!-- Footer -->
  <div class="footer">
     <p> All rights reserved. © 2019 Copyright:
       <a href="https://www.napsa.co.zm/">napsa.co.zm</a>
     </p>
  </div>


</body>
</html>
