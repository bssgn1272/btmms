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


$data = new Database();
$array = null;
$result= $data->getPriceRate();
if ($result->num_rows > 0) {

	while($row = $result->fetch_assoc()) {
	$array[] = $row;
	}

}
$priceRate =$array[0]['rate'];

 

?>
</style>
<body>
   

<table >
    
<tr>
    <td >
        
 <div >
             <img src="logo/logo.png"  style="width:450px;height:350px; margin-bottom:10px; margin-top: 100px; margin-left:80px;" >
             <br>
             
    </div>

    </td>
     <td><div  id="">

  <!-- html form  for luggage  management system  -->

  
       <div class="container-contact100">
        <div class="wrap-contact100">

      

        <form name = "form1" action="API/updateRate.php" method = "post" class="contact100-form validate-form" >
           <!--  <fieldset>
                <legend></legend> -->
                 <span class="contact100-form-title">
                    View Rate
                </span>
                    <div class="wrap-input100 validate-input" data-validate="First name is required">
                        <div class="col-sm-10">
                           <label class="label-input100" >Current Rate:</label>
                           <input class="input100" type = "text" name = "rate" value = "<?php echo"$priceRate";?>" required />
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
