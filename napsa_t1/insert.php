<html>
<head>
	<title></title>
</head>
<body>
<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "napsadb");
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
 
// Escape user inputs for security
$luggage_id = mysqli_real_escape_string($link, $_REQUEST['luggage_id']);
$fname = mysqli_real_escape_string($link, $_REQUEST['fname']);
$lname = mysqli_real_escape_string($link, $_REQUEST['lname']);
$description = mysqli_real_escape_string($link, $_REQUEST['description']);
$weight = mysqli_real_escape_string($link, $_REQUEST['weight']);
$cost = mysqli_real_escape_string($link, $_REQUEST['cost']);
$recipient_id= mysqli_real_escape_string($link, $_REQUEST['reciient_id']);
$Destination = mysqli_real_escape_string($link, $_REQUEST['Destination']);
 
// Attempt insert query execution
$sql = "INSERT INTO `luggage` (luggage_id , fname, lname, description, weight, cost, reciient_id, Destination ) VALUES (        '$luggage_id' , '$fname', '$lname', '$description', '$weight', '$cost', '$reciient_id', '$Destination')";
if(mysqli_query($link, $sql)){
    echo '<script>
            $(function() {
                $( "#dialog" ).dialog({
                    autoOpen: true,
                    modal: true,
                    width: 300,
                    height: 200,
                  });
            });
             </script>'
              .'<div id="dialog" title="Basic dialog">
                     <p>Records added successfully</p>
               </div>';
} else{
    echo "ERROR: Could not be able to execute $sql. " . mysqli_error($link);
}
// Close connection
mysqli_close($link);
?>

</body>
</html>