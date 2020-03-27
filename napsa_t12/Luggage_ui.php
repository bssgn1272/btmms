<!DOCTYPE html>
<?php
session_start();

?>
<html lang="en">
<head>
<link rel="stylesheet" href="luggage.css">
</head>
<style>
  

</style>
<body>
   

<table >
    
<tr>
    <td >
        
 <div >
             <img src="logo/logo.png"  style="width:450px;height:350px; margin-bottom:10px; margin-top: 100px; margin-left:80px;" >
             <br>
             <h2 style="margin-left: 80px;">Laguage Management System Manual </h2>
             <p class="text-muted" style="margin-left: 80px;">Use XXXX-XXX-XXX format for Receipients contact details </p>
             <p class="text-primary" style="margin-left: 80px;">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
             <p class="text-secondary" style="margin-left: 80px;">Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
             <p class="text-warning" style="margin-left: 80px;">Etiam porta sem malesuada magna mollis euismod.</p>
             <p class="text-danger" style="margin-left: 80px;">Donec ullamcorper nulla non metus auctor fringilla.</p>
             <p class="text-success" style="margin-left: 80px;">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
             <p class="text-info" style="margin-left: 80px;">Maecenas sed diam eget risus varius blandit sit amet non magna.</p>
    </div>

    </td>
     <!-- <td><div  id=""> -->

  <!-- html form  for luggage  management system  -->

   <?php

$filename = 'weight.txt';
$fp = fopen($filename, "r") or die("Unable to open file");

$content = fread($fp, filesize($filename));
fclose($fp);
$weight = $content;

?>
       <div class="container-contact100">
        <div class="wrap-contact100">

      

        <form name = "form1" action="API/addLuggage.php" method = "post" class="contact100-form validate-form" >
           <!--  <fieldset>
                <legend></legend> -->
                 <span class="contact100-form-title">
                    Welcome To Napsa Luggage Management System 
                </span>
                    <div class="wrap-input100 validate-input" data-validate="First name is required">
                        <div class="col-sm-10">
                           <label class="label-input100" >First Name:</label>
                           <input class="input100" type = "text" name = "fname" value = "" required minlength="4" maxlength="35"/>
                           <span class="focus-input100"></span>
                        </div>
                     </div>
                      <div class="wrap-input100 validate-input" data-validate="Last name is required">
                        <div class="col-sm-10">
                             <label class="label-input100" >Last Name:</label>
                             <input class="input100" type = "text" name = "lname" value = "" required minlength="4" maxlength="35"/>
                             <span class="focus-input100"></span>
                        </div>
                     </div>

                    <div class=" wrap-input100 validate-input" data-validate = "Description is required">
                          <label class="label-input100"  for="exampleTextarea">Description</label>
                          <textarea class="input100" TYPE="text" name="description" value="" required  minlength="4" maxlength="40"rows="3"></textarea>
                          <span class="focus-input100"></span>
                    </div>
                     <div class="wrap-input100 validate-input" data-validate="Weight from scale required">
                        <div class="col-sm-10">
                            <label class="label-input100" >Weight:</label>
                            <input  class="input100" type = "text" name = "weight" value = "<?php echo"$weight";?>" required min="1" max="3" />
                            <span class="focus-input100"></span>
                        </div>
                     </div>
                      <div class="wrap-input100 validate-input" data-validate="Phone number is required">
                        <div class="col-sm-10">
                            <label class="label-input100" >Recipient:</label>
                            <input  class="input100" type = "tel" name ="recipient_id" value = "" required  pattern="^\d{4}-\d{3}-\d{3}$" title="Use XXXX-XXX-XXX format" 
                            />
                            <span class="focus-input100"></span>
                        </div>
                     </div>
                      <div class="wrap-input100 validate-input" data-validate="destination is required">
                        <div class="col-sm-10">
                            <label class="label-input100" >Destination:</label>
                            <input class="input100" type = "text" name = "Destination" value = "" required />
                            <span class="focus-input100"></span>
                        </div>
                     </div class="container-contact100-form-btn">
                     <div>
                          <button type="submit" name="submit" class="contact100-form-btn" value="Submit">Submit</button>

                     </div>
        </form>
    </div>
</div>
    </td>
</tr>
</table>

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
