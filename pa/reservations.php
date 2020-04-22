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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/scroll.css">
     <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
     <link href='https://fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
    <meta http-equiv="refresh" content="45; url=http://localhost/pa/departures.php">
    <!--   Title -->
    <title>Public Announcement System</title>
  </head>
  <body>

    <!-- Main Page content -->
    <div class="content">
      <div class="row" style="background-color: #03396c;" >
        <div class="col">
          <h2>
            <i class='material-icons' style=" margin-top: 0px;   font-size:50px; text-align: center;" >&#xe530</i>
            RESERVATIONS
          </h2>
        </div>
        <div class="col " >
          <div>
            <!--  Empty Div -->
          </div>
        </div>
        <div class="col ">
          <div class="time">
            <p style="font-family: 'Orbitron', sans-serif;"><?php echo date("H:i: A") . "\n"; ?></p>
          </div>
        </div>
      </div>

        <!-- table -->
      <table class="table bg-primary">
        <thead style="  font-size: 25px;">
          <tr class="table-light">
            <th>Bus</th>
            <th>Slot</th>
            <th>Route</th>
            <th>Time</th>
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

          $sql = "SELECT * FROM ed_reservations
                     WHERE  status = 'A'
                       ORDER BY time";

          $result =$con ->query($sql);
          // echo print_r($result,true);
          if ($result->num_rows > 0) {
             // output data of each row
           while($row = $result->fetch_assoc()) {
              echo "<tr>";
                echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["id"] .  "</td>";
                echo "<td style=' font-size: 20px;color: #ffffff'>"  . $row["slot"].  "</td>";
                echo "<td style=' font-size: 20px;color: #ffffff'>"  . $row["route"] .  "</td>";
  							echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["reserved_time"] .  "</td>";
  							echo "<td style=' font-size: 20px; color: #ffffff'>"  . $row["status"] .  "</td>";
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
            <th> ANNOUNCEMENTS</th>
          </tr>
        </thead>
      </table>
      <!--  RSS Feed  -->
      <div >
        <span>
          <?php include'messages.php';?>
        </span>
      </div>
    </div>

    <br>
    <!-- Footer -->
      <div class="footer">
        <marquee   > Welcome ! Kindly find your bus and head to the designated waiting area . Enjoy your trip .<?php echo date('l jS \of F Y ');?></marquee>
      </div>
  </body>
</html>
