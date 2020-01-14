<!DOCTYPE html>
<html lang="en">
<head>

</head>
<style>
    *{font-family:arial,helvetica,sans-serif;}

h1_{margin-top:100px;}
h2{margin:0 0 10px 0; font-size:20px; font-weight:normal; color:#757575;}
h2 span{top:-7px; position:relative; left:-3px;}
h2 span i{font-size:13px;}


input, textarea{ border:0; border-bottom: 1px solid #666; font-size:15px; padding:3px; width:250px; resize: none;}

input:focus, textarea:focus{background:white; border:0; border-bottom:1px solid #4D90FE; box-shadow:none;outline: none; }
/*input.contador {width: 22px !important; text-align: center; color: green; border: none; font-size: 12px !important; float:right;}
*/


    div#envelope{
width: 55%;
margin: 10px 30% 10px 25%;
padding:10px 0;
border: 2px solid gray;
border-radius:10px;
}

/*form{
width:70%;
margin:4% 15%;
}*/

@import url(http://fonts.googleapis.com/css?family=Roboto+Slab);
* {
/* With these codes padding and border does not increase it's width and gives intuitive style.*/

-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
}
/*body {
margin:0;
padding:0;
font-family: 'Roboto Slab', serif;
}*/
div#envelope{
width: 100%;
margin: 10px 30% 10px 25%;
padding:10px 0;
border: 0px solid gray;
border-radius:10px;
}
form{
width:80%;
overflow-x: hidden;
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

#submit:hover
{
background-color: black;
}


fieldset{
border: 0.1px solid gray;
/*box-shadow: 0 0 10px gray;
*/}

.container{
float : left;
 width:100%;
}

table{
    max-width:100%;
    margin-left: 0px;
    border-spacing: 20px;
    border-collapse: separate;


}

.container-contact100 {
  width: 100%;  
  min-height: 100vh;
  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
  background: #f2f2f2;
  
}
.wrap-contact100 {
  width: 100%;
  background: #fff;
  overflow: hidden;
  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  flex-wrap: wrap;
  align-items: stretch;
  flex-direction: row-reverse;

}
.contact100-form {
  width: 560px;
  min-height: 100vh;
  display: block;
  padding: 110px 55px 55px 55px;
}
.wrap-input100 {
  width: 100%;
  position: relative;
  border: 1px solid #e6e6e6;
  border-radius: 10px;
  margin-bottom: 20px;
}
.label-input100 {
  font-family: Montserrat-SemiBold;
  font-size: 11px;
  color: #666666;
  line-height: 1.2;
  text-transform: uppercase;
  padding: 15px 0 2px 24px;
}
.input100 {
  display: block;
  width: 100%;
  background: transparent;
  font-family: Montserrat-Regular;
  font-size: 18px;
  color: #404b46;
  line-height: 1.2;
  padding: 0 26px;
}
input.input100 {
  height: 48px;
}



textarea.input100 {
  min-height: 130px;
  padding-top: 14px;
  padding-bottom: 15px;
}
.focus-input100 {
  position: absolute;
  display: block;
  width: calc(100% + 2px);
  height: calc(100% + 2px);
  top: -1px;
  left: -1px;
  pointer-events: none;
  border: 1px solid #6675df;
  border-radius: 10px;

  visibility: hidden;
  opacity: 0;

  -webkit-transition: all 0.4s;
  -o-transition: all 0.4s;
  -moz-transition: all 0.4s;
  transition: all 0.4s;

  -webkit-transform: scaleX(1.1) scaleY(1.3);
  -moz-transform: scaleX(1.1) scaleY(1.3);
  -ms-transform: scaleX(1.1) scaleY(1.3);
  -o-transform: scaleX(1.1) scaleY(1.3);
  transform: scaleX(1.1) scaleY(1.3);
}
.contact100-form-title {
  width: 100%;
  display: block;
  font-family: Poppins-Regular;
  font-size: 30px;
  color: #333333;
  line-height: 1.2;
  text-align: center;
  padding-bottom: 48px;
}
.validate-input {
  position: relative;
}
.container-contact100-form-btn {
  width: 100%;
  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding-top: 10px;
}

.contact100-form-btn {
  display: -webkit-box;
  display: -webkit-flex;
  display: -moz-box;
  display: -ms-flexbox;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0 20px;
  width: 100%;
  height: 50px;
  border-radius: 10px;
  background: #fbaa1d;

  font-family: Montserrat-Bold;
  font-size: 12px;
  color: #fff;
  line-height: 1.2;
  text-transform: uppercase;
  letter-spacing: 1px;

  -webkit-transition: all 0.4s;
  -o-transition: all 0.4s;
  -moz-transition: all 0.4s;
  transition: all 0.4s;
}

.contact100-form-btn:hover {
  background: #1f5b70;
}




</style>
<body>
   

<table >
    
<tr>
    <td >
        
 <div >
             <img src="logo/logo.png"  style="width:450px;height:350px; margin-bottom:10px; margin-top: 100px; margin-left:80px;" >
            <!--  <br>
             <h2>Laguage Management System Manual </h2>
             <p class="text-muted">Fusce dapibus, tellus ac cursus commodo, tortor mauris nibh.</p>
             <p class="text-primary">Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
             <p class="text-secondary">Pellentesque ornare sem lacinia quam venenatis vestibulum.</p>
             <p class="text-warning">Etiam porta sem malesuada magna mollis euismod.</p>
             <p class="text-danger">Donec ullamcorper nulla non metus auctor fringilla.</p>
             <p class="text-success">Duis mollis, est non commodo luctus, nisi erat porttitor ligula.</p>
             <p class="text-info">Maecenas sed diam eget risus varius blandit sit amet non magna.</p> -->
    </div>

    </td>
     <td><div  id="">

  <!-- html form  for luggage  management system  -->

   <?php

$filename = 'http://localhost/napsa_t1/weight.txt';
$fp = fopen($filename, "r") or die("Unable to open file");

$content = fread($fp, filesize($filename));
fclose($fp);
$weight = $content;

?>
       <div class="container-contact100">
        <div class="wrap-contact100">

      

        <form name = "form1" action="API/createLuggage.php" method = "post" class="contact100-form validate-form" >
           <!--  <fieldset>
                <legend></legend> -->
                 <span class="contact100-form-title">
                    Welcome To Napsa Luggage Management System 
                </span>
                    <div class="wrap-input100 validate-input" data-validate="First name is required">
                        <div class="col-sm-10">
                           <label class="label-input100" >First Name:</label>
                           <input class="input100" type = "text" name = "fname" value = "" required/>
                           <span class="focus-input100"></span>
                        </div>
                     </div>
                      <div class="wrap-input100 validate-input" data-validate="Last name is required">
                        <div class="col-sm-10">
                             <label class="label-input100" >Last Name:</label>
                             <input class="input100" type = "text" name = "lname" value = "" required/>
                             <span class="focus-input100"></span>
                        </div>
                     </div>

                    <div class=" wrap-input100 validate-input" data-validate = "Description is required">
                          <label class="label-input100"  for="exampleTextarea">Description</label>
                          <textarea class="input100" TYPE="text" name="description" value="" required  rows="3"></textarea>
                          <span class="focus-input100"></span>
                    </div>
                     <div class="wrap-input100 validate-input" data-validate="Weight from scale required">
                        <div class="col-sm-10">
                            <label class="label-input100" >Weight:</label>
                            <input  class="input100" type = "text" name = "weight" value = "<?php echo"$weight";?>" required />
                            <span class="focus-input100"></span>
                        </div>
                     </div>
                      <div class="wrap-input100 validate-input" data-validate="Phone number is required">
                        <div class="col-sm-10">
                            <label class="label-input100" >Recipient:</label>
                            <input  class="input100" type = "text" name = "recipient_id" value = "" required />
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
                          <button type="submit" class="contact100-form-btn" value="Submit">Submit</button>

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