this s a file that instructs how to create install the passenger_expirence module 
this was created using php and should be run on a webserver
the module contains the following files and folders

1.Images folder where all the generated images will be saved 
2. logs this is where all the log files are located
3. CORE this contains the Barcode library that is used to generate the barcode and connect to the database
4.AccessControlModule this is the class that receives the data and generaates the barcode this class expects a date as the main parameter and generates the barcode image and saves it in the images folder
4. the index.php is the clas that is displayed on the webbrowser
5.other helper classes for css and styling
++++++++++++++++++++++++++HOW TO INSTALL+++++++++++++++++++++++++++++++++++++
1. update the database connection parameters in the database.php file which is in CORE folder

change the following on line 17 in database.php

$servername = "localhost";
$username = "root";
$password = "M1LL10n$";
$dbname = "napsa";

change these to appropriate values used in your database connection

