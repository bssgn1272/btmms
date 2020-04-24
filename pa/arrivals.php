<?php
require 'Configs.php'; 
			session_start();
      date_default_timezone_set("Africa/Lusaka");

?>


<!DOCTYPE html>
<html lang="en" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity=" sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/scroll.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
    <link href="css/ticker-style.css" rel="stylesheet" type="text/css" />
     <meta http-equiv="refresh" content="45; url=http://localhost/pa/reservations.php">

    <title>Public Announcement System</title>
  </head>
  
  <body>
    <!-- Main Page content -->
    <main>
    <div class="content">
      <div class="row" style="background-color: #03396c;" >
        <div class="col">
          <h2>
            <i class='material-icons' style=" margin-top: 0px;   font-size:50px; text-align: center;" >&#xe530</i>
            ARRIVALS
          </h2>
        </div>
        <div class="col " >
          <div>
            <!--  Empty Div -->
          </div>
        </div>
        <div class="col ">
          <div class="time">
            <p style="font-family: 'Orbitron', sans-serif;"><?php echo date("H:i A") . "\n"; ?></p>
          </div>
        </div>
      </div>

      <!-- table -->
      <table class="table bg-primary">
        <thead style="  font-size: 25px;">
          <tr class="table-light">
            <th>No</th>
            <th>Time</th>
            <th>From</th>
            <th>Bus</th>
			      <th>Bay</th>
			      <th>Status</th>
          </tr>
        </thead>


        <?php

          $con = new mysqli(Configs::servername,Configs::username,Configs::password,Configs::dbname);
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
						            ed_arrivals AS b
						      INNER JOIN ed_company AS c
						               ON b.company_id = c.company_id
                  INNER JOIN ed_route AS r
					                 ON b.route_id = r.route_id
						      INNER JOIN ed_bay as k
						               ON b.bay_id = k.bay_id
						      INNER JOIN ed_status as s
						               ON b.status_id = s.status_id
											     ORDER BY time";

          $result =$con ->query($sql);

          if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
              echo "<tr>";
                echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["bus_number"] .  "</td>";
                echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["time"].  "</td>";
                echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["route_destination"] .  "</td>";
                echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["company_name"] .  "</td>";
							  echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["bay_name"] .  "</td>";
							  echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["status_message"] .  "</td>";
              echo "</tr>";
            }
              echo "</table>";
          }
          $con ->close();

        ?>
      </table>
    </div>

        <div class=test>
          <table class="table br-primary">
            <thead style="  font-size: 20px;">
              <tr class="table-light">
                <th> 
                   ANNOUNCEMENTS
                </th>
              </tr>
            </thead>
          </table>
          <!-- RSS Feed  -->
          <div >
            <span>
            Welcome ! Kindly find your bus and head to the designated waiting area . Enjoy your trip 
            </span>
          </div>
        </div>


        <br>

        <!-- Footer -->
        <div class="footer">
          <div id="ticker-wrapper" class="no-js">
            <ul id="js-news" class="js-hidden">
                <li class="news-item"><a href="#">This is the 1st latest news item.</a></li>
                <li class="news-item"><a href="#">This is the 2nd latest news item.</a></li>
                <li class="news-item"><a href="#">This is the 3rd latest news item.</a></li>
                <li class="news-item"><a href="#">This is the 4th latest news item.</a></li>
            </ul>
          </div>
        </div>
        <!-- partial -->
         <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/1.6.4/jquery.js'></script><script  src="./script.js"></script>

      </main>
      

  </body>
</html>
