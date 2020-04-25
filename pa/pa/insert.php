<?php
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
$link = mysqli_connect("localhost", "root", "", "napsa");

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

// Escape user inputs for security
$description = mysqli_real_escape_string($link, $_REQUEST['description']);
$sql = ("INSERT INTO ed_posts (description) VALUES('$description')"); //prepare sql insert query
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
//Close connection
mysqli_close($link);

?>