<?php

?>
<!DOCTYPE html>
<head>
	<link rel="stylesheet" media="screen and (max-width: Xpx) and (min-width: Ypx)" href="style.css" />
</head>
<html>
<style>
	div#envelope{
width: 55%;
margin: 10px 30% 10px 25%;
padding:10px 0;
border: 2px solid gray;
border-radius:10px;
}

form{
width:70%;
margin:4% 15%;
}

@import url(http://fonts.googleapis.com/css?family=Roboto+Slab);
* {
/* With these codes padding and border does not increase it's width and gives intuitive style.*/

-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
}
body {
margin:0;
padding:0;
font-family: 'Roboto Slab', serif;
}
div#envelope{
width: 55%;
margin: 10px 30% 10px 25%;
padding:10px 0;
border: 0px solid gray;
border-radius:10px;
}
form{
width:70%;
margin:4% 15%;
}
header{
background-color: #4180C5;
text-align: center;
padding-top: 12px;
padding-bottom: 8px;
margin-top: -11px;
margin-bottom: -8px;
border-radius: 10px 10px 0 0;
color: aliceblue;
}

/* Makes responsive fields. Sets size and field alignment.*/
input[type=text]{
margin-bottom: 20px;
margin-top: 10px;
width:100%;
padding: 15px;
border-radius:5px;
border:1px solid #ffae42;
}
input[type=submit]
{
margin-bottom: 20px;
width:100%;
padding: 15px;
border-radius:5px;
border:1px solid #ffae42;
background-color: #4180C5;
color: aliceblue;
font-size:15px;
cursor:pointer;
}
#submit:hover
{
background-color: black;
}
textarea{
width:100%;
padding: 15px;
margin-top: 10px;
border:1px solid #ffae42;
border-radius:5px;
margin-bottom: 20px;
resize:none;
}
input[type=text]:focus,textarea:focus {
border-color: #ffae42;
}
fieldset{
border: 0.1px solid gray;
box-shadow: 0 0 10px gray;
}

</style>
<body>
<?php

$filename = 'http://localhost/napsa_t1/weight.txt';
$fp = fopen($filename, "r") or die("Unable to open file");

$content = fread($fp, filesize($filename));
fclose($fp);
$weight = $content;

?>
	<div  id="envelope">
 <form name = "form1" action="API/createLuggage.php" method = "post" >
 	   	<header> Luggage Form </header>
        <fieldset>


            <div  class = "container">
                <div class = "form_group">
                    <label>First Name:</label>
                    <input type = "text" name = "fname" value = "" required/>
                </div>
                <div class = "form_group">

                    <label>Last Name:</label>
                    <input type = "text" name = "lname" value = "" required/>
                </div>

                <div class = "form_group">
                    <label>Description:</label>
                     <textarea TYPE="text" name="description" value="" required   ></textarea>
                </div>
                </div>

                <div class = "form_group">
                    <label>Weight:</label>
                    <input type = "text" name = "weight" value = "<?php echo"$weight";?>" required />
                </div>

                <div class = "form_group">
                    <label>Recipient:</label>
                    <input type = "text" name = "recipient_id" value = "" required />
                </div>

                <div class = "form_group">
                    <label>Destination:</label>
                    <input type = "text" name = "Destination" value = "" required />
                </div>

                <div>
                	 <input type="submit" class="btn btn-primary" value="Submit">
                </div>


            </div>
            </fieldset>
        </form>
        </div>
</body>
</html>




