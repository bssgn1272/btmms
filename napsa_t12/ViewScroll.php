<!DOCTYPE html>
<?php 
include "API/database.php";

?>
<html lang="en">
<head>
<link rel="stylesheet" href="luggage.css">
</head>
<style>
  
<?php 


 

//$filename = 'weight.txt';
$filename = 'scroll.txt';
$fp = fopen($filename, "r") ;

$content = fread($fp, filesize($filename));
fclose($fp);
$weight = $content;



?>
</style>
<body>
   

<table >
    
<tr>
 
     <td><div  id="">

  <!-- html form  for luggage  management system  -->

  
       <div class="container-contact100">
        <div class="wrap-contact100">

      

        <form name = "form1" action="#" method = "post" class="contact100-form validate-form" >
           <!--  <fieldset>
                <legend></legend> -->
                 <span class="contact100-form-title">
                    View Rate
                </span>
                    <div class="wrap-input100 validate-input" data-validate="First name is required">
                        
<div class=" wrap-input100 validate-input" data-validate = "Description is required">
                          <label class="label-input100"  for="exampleTextarea">Description</label>
                          <textarea class="input100" TYPE="text" name="scroll" value=""><?php echo"$weight"?></textarea>
                          <span class="focus-input100"></span>
                    </div>

                     </div>
             
                  
               
                     <div>
                          <button type="submit" name="submit" class="contact100-form-btn" value="Submit">Update</button>

                     </div>
        </form>
    </div>
</div>
    </td>
</tr>
</table>


<?php

if(isset($_REQUEST['submit'])){
$scroll=$_POST['scroll'];
/**
$fp = fopen($filename, "r") ;

$content = fread($fp, filesize($filename));
fclose($fp);
$weight = $content;
*/
$filename = 'scroll.txt';
$fp = fopen($filename, "w") ;


//replace_string_in_file($filename, $weight, $scroll);
fwrite($fp, $scroll);

fclose($fp);
header("Refresh:0");

}


?>

<!--===============================================================================================-->
  <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
  <script>
    $(".js-select2").each(function(){
      $(this).on('select2:open', function (e){
        $(this).parent().next().addClass('eff-focus-selection');
      });
    });
    $(".js-select2").each(function(){
      $(this).on('select2:close', function (e){
        $(this).parent().next().removeClass('eff-focus-selection');
      });
    });



  </script>
<!--===============================================================================================-->
  <script src="js/main.js"></script>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-23581568-13"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-23581568-13');
  </script>

</body>
</html>
