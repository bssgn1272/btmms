
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE HTML>
<html>
<head>
<title>SMSAPP</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="SMSAPP" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery.min.js"> </script>
<script src="js/bootstrap.min.js"> </script>
</head>
<body>
	<div class="login">
		<h1><a href="/">SMSAPP </a></h1>
		<div class="login-bottom">
			<?php if($this->session->flashdata('error')){ ?>
				<div class="alert alert-danger" role="alert">
					<strong>Oh Snap!</strong> <?php echo $this->session->flashdata('error'); unset($_SESSION['error']); ?>
				</div>
			<?php } ?>
			<h2>Login</h2>
			<form action="auth/login" method="post">
			<div class="col-md-8">
				<div class="login-mail">
					<input type="email" placeholder="Email" name="email" required="">
					<i class="fa fa-envelope"></i>
				</div>
				<div class="login-mail">
					<input type="password" placeholder="Password" name="password" required="">
					<i class="fa fa-lock"></i>
				</div>
				   <!--<a class="news-letter " href="#">
						<label class="checkbox1"><input type="checkbox" name="checkbox" ><i> </i>Forget Password</label>
					</a>-->

			
			</div>
			<div class="col-md-4 login-do">
				<label class="hvr-shutter-in-horizontal login-sub">
					<input type="submit" value="login">
				</label>
				<!--<p>Do not have an account?</p>
				<a href="signup.html" class="hvr-shutter-in-horizontal">Signup</a>-->
			</div>
			
			<div class="clearfix"> </div>
			</form>
		</div>
	</div>
		<!---->
<div class="copy-right">
    <p> &copy; 2021 SMSAPP. All Rights Reserved | Design by <a href="http://eyedsystems.co.zm/" target="_blank">Eyed-D Systems</a> </p>
</div>  
<!---->
<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
</body>
</html>

