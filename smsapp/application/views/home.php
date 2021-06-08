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
  
<!-- Mainly scripts -->
<script src="js/jquery.metisMenu.js"></script>
<script src="js/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<link href="css/custom.css" rel="stylesheet">
<script src="js/custom.js"></script>
<script src="js/screenfull.js"></script>
		<script>
		$(function () {
			$('#supported').text('Supported/allowed: ' + !!screenfull.enabled);

			if (!screenfull.enabled) {
				return false;
			}

			

			$('#toggle').click(function () {
				screenfull.toggle($('#container')[0]);
			});
			

			
		});
		</script>



</head>
<body>
<div id="wrapper">
       <!----->
        <nav class="navbar-default navbar-static-top" role="navigation">
             <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
               <h1> <a class="navbar-brand" href="home">SMSAPP</a></h1>         
			   </div>
			 <div class=" border-bottom">
        	<div class="full-left">
            <div class="clearfix"> </div>
           </div>
     		<div class="drop-men" >
		        <ul class=" nav_1">
		           
		    		<li class="dropdown">
		              <a href="#" class="dropdown-toggle dropdown-at" data-toggle="dropdown"><span class=" name-caret"><?php echo $this->aauth->get_user()->username; ?><i class="caret"></i></span></a>
		              <ul class="dropdown-menu " role="menu">
		                <li><a href="auth/logout"><i class="fa fa-user"></i>Logout</a></li>
		              </ul>
		            </li>
		           
		        </ul>
		     </div><!-- /.navbar-collapse -->
			<div class="clearfix">
       
     </div>
	  
		    <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
				
                    <li>
                        <a href="home" class=" hvr-bounce-to-right"><i class="fa fa-dashboard nav_icon "></i><span class="nav-label">Home</span> </a>
                    </li>
                   
                    <!--<li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-indent nav_icon"></i> <span class="nav-label">Menu Levels</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="graphs.html" class=" hvr-bounce-to-right"> <i class="fa fa-area-chart nav_icon"></i>Graphs</a></li>
                            
                            <li><a href="maps.html" class=" hvr-bounce-to-right"><i class="fa fa-map-marker nav_icon"></i>Maps</a></li>
			
						<li><a href="typography.html" class=" hvr-bounce-to-right"><i class="fa fa-file-text-o nav_icon"></i>Typography</a></li>

					   </ul>
                    </li>
					 <li>
                        <a href="inbox.html" class=" hvr-bounce-to-right"><i class="fa fa-inbox nav_icon"></i> <span class="nav-label">Inbox</span> </a>
                    </li>
                    
                    <li>
                        <a href="gallery.html" class=" hvr-bounce-to-right"><i class="fa fa-picture-o nav_icon"></i> <span class="nav-label">Gallery</span> </a>
                    </li>
                     <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-desktop nav_icon"></i> <span class="nav-label">Pages</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="404.html" class=" hvr-bounce-to-right"> <i class="fa fa-info-circle nav_icon"></i>Error 404</a></li>
                            <li><a href="faq.html" class=" hvr-bounce-to-right"><i class="fa fa-question-circle nav_icon"></i>FAQ</a></li>
                            <li><a href="blank.html" class=" hvr-bounce-to-right"><i class="fa fa-file-o nav_icon"></i>Blank</a></li>
                       </ul>
                    </li>
                     <li>
                        <a href="layout.html" class=" hvr-bounce-to-right"><i class="fa fa-th nav_icon"></i> <span class="nav-label">Grid Layouts</span> </a>
                    </li>
                   
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-list nav_icon"></i> <span class="nav-label">Forms</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="forms.html" class=" hvr-bounce-to-right"><i class="fa fa-align-left nav_icon"></i>Basic forms</a></li>
                            <li><a href="validation.html" class=" hvr-bounce-to-right"><i class="fa fa-check-square-o nav_icon"></i>Validation</a></li>
                        </ul>
                    </li>
                   
                    <li>
                        <a href="#" class=" hvr-bounce-to-right"><i class="fa fa-cog nav_icon"></i> <span class="nav-label">Settings</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="signin.html" class=" hvr-bounce-to-right"><i class="fa fa-sign-in nav_icon"></i>Signin</a></li>
                            <li><a href="signup.html" class=" hvr-bounce-to-right"><i class="fa fa-sign-in nav_icon"></i>Singup</a></li>
                        </ul>
                    </li> -->
                </ul>
            </div>
			</div>
        </nav>
		 <div id="page-wrapper" class="gray-bg dashbard-1">
       <div class="content-main">
 
 	<!--banner-->	
		     <div class="banner">
		    	<h2>
				<a href="home">Home</a>
				<i class="fa fa-angle-right"></i>
				<span>Operations</span>
				</h2>
		    </div>
		<!--//banner-->
 	 <!--faq-->
 	<div class="blank">
		<div class="blank-page">
			<div class="grid-form">
				<div class="grid-form1">
					<h3 id="forms-example" class="">Send Single SMS</h3>
					<?php if($this->session->flashdata('singleerror')){ ?>
						<div class="alert alert-danger" role="alert">
							<strong>Oh Snap!</strong> <?php echo $this->session->flashdata('singleerror'); unset($_SESSION['singleerror']); ?>
						</div>
					<?php } ?>
					<?php if($this->session->flashdata('singlesuccess')){ ?>
						<div class="alert alert-success" role="alert">
							<strong>Great!</strong> <?php echo $this->session->flashdata('singlesuccess'); unset($_SESSION['singlesuccess']); ?>
						</div>
					<?php } ?>
					<form action="single" method="post">
						<div class="form-group">
							<label for="exampleInputEmail1">Phone Number</label>
							<input type="number" min="260750000000" max="260979999999" name="phonenumber" required class="form-control" id="exampleInputEmail1" placeholder="Phone Number">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Message</label>
							<textarea name="message" id="txtarea1" cols="50" rows="8" required class="form-control1"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Send</button>
					</form>
				</div>
				<div class="grid-form1">
					<h3 id="forms-example" class="">Upload CSV File Containing Phone Numbers</h3>
					<?php if($this->session->flashdata('csverror')){ ?>
						<div class="alert alert-danger" role="alert">
							<strong>Oh Snap!</strong> <?php echo $this->session->flashdata('csverror'); unset($_SESSION['csverror']); ?>
						</div>
					<?php } ?>
					<?php if($this->session->flashdata('csvsuccess')){ ?>
						<div class="alert alert-success" role="alert">
							<strong>Great!</strong> <?php echo $this->session->flashdata('csvsuccess'); unset($_SESSION['csvsuccess']); ?>
						</div>
					<?php } ?>
					<form action="csvfile" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="exampleInputEmail1">CSV File</label>
							<input type="file" name="csvfile" required class="form-control" id="exampleInputEmail1" placeholder="Select File...">
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Message</label>
							<textarea name="message" id="txtarea1" cols="50" rows="8" required class="form-control1"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Send</button>
					</form>
				</div>
				<div class="grid-form1">
					<h3 id="forms-example" class="">Send To Group(s)</h3>
					<?php if($this->session->flashdata('grouperror')){ ?>
						<div class="alert alert-danger" role="alert">
							<strong>Oh Snap!</strong> <?php echo $this->session->flashdata('grouperror'); unset($_SESSION['grouperror']); ?>
						</div>
					<?php } ?>
					<?php if($this->session->flashdata('groupsuccess')){ ?>
						<div class="alert alert-success" role="alert">
							<strong>Great!</strong> <?php echo $this->session->flashdata('groupsuccess'); unset($_SESSION['groupsuccess']); ?>
						</div>
					<?php } ?>
					<form action="group" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<h4>Select one or more groups</h4>
							<input id="check1" type="checkbox" name="groups[]" value="BUS OPERATOR" />
							<label for="check1">Bus Operators &nbsp;</label>
							<input id="check2" type="checkbox" name="groups[]" value="MARKETER" />
							<label for="check1">Marketeers &nbsp;</label>
							<input id="check2" type="checkbox" name="groups[]" value="TRAVELER" />
							<label for="check1">Travelers &nbsp;</label>
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">Message</label>
							<textarea name="message" id="txtarea1" cols="50" rows="8" required class="form-control1"></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Send</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<!--//faq-->
		<!---->
<div class="copy">
            <p> &copy; 2021 SMSAPP. All Rights Reserved | Design by <a href="http://eyedsystems.co.zm/" target="_blank">Eye-D Systems</a> </p>	    </div>
		</div>
		</div>
		<div class="clearfix"> </div>
       </div>
     
<!---->
<!--scrolling js-->
	<script src="js/jquery.nicescroll.js"></script>
	<script src="js/scripts.js"></script>
	<!--//scrolling js-->
</body>
</html>

