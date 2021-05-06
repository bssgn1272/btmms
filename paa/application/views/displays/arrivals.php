<!DOCTYPE html>
<html lang="en">
<head>
	<title>ARRIVALS</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="/paa/static/departures/images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/css/util.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/css/main.css">

    <style type="text/css">
        .Offloading {
            color: lime !important;
        }

        .Cancelled {
            color: red !important;
        }

        .Arrived {
            color: orange !important;
        }
		
		.Departed {
            color: orange !important;
        }
		
		.Delayed {
            color: lightcoral !important;
        }
    </style>
</head>
<body id="body">
    <div>
		<!--<div class="row">
			<div style="margin-top:100px">
				&nbsp;
			</div>
		</div>-->
        <div class="row">
            <div class="container-table100">
                <span class="'table100-head top-header">
                    <div class="row">
                        <div class="'col-md-4" style="padding-top:10px;">
                            <h1 style="font-weight:bold;color:black;float:right !important;">
                                <span id="date">00-00-00</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </h1>
                        </div>
                        <div class="'col-md-4">
                            <h1 style="font-weight:bold;color:black;font-size:3.5em">ARRIVALS</h1>
                            <center>
                                <span style="font-weight:bold;">
                                    [<span id="page">1/1</span>]
                                </span>
                            </center>
                        </div>
                        <div class="'col-md-4" style="padding-top:10px;">
                            <h1 style="font-weight:bold;color:black;float:right !important;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span  id="time">00:00:00</span>
                            </h1>
                        </div>
                    </div>
                </span>
                <div class="wrap-table100">
                    <div class="table100">
                        <table>
                            <thead>
                                <tr class="table100-head">
                                    <th class="">Operator</th>
                                    <!--th class="">No.</th-->
									<th class="">Source</th>
                                    <th class="">Time</th>                               
                                    <th class="">Gate</th>
                                    <th class="">Status</th>
                                </tr>
                            </thead>
                            <tbody id="tb">
                                <?php $i = 0; ?>
                                <?php foreach($arrivals as $arrival){ ?>
                                <?php if($i >= 10){ break; } ?>
                                    <tr>
                                        <td class=""><?php echo $arrival->company_name." (".$arrival->bus_number.")"; ?></td>
                                        <td class=""><?php echo $arrival->route_destination; ?></td>
                                        <td class=""><?php echo $arrival->time; ?></td>
                                        <td class=""><center><?php echo $arrival->bay_name; ?></center></td>
                                        <td class="<?php echo $arrival->status_message; ?>" id="status"><?php echo $arrival->status_message; ?></td>
                                    </tr>  
                                <?php $i++; ?>       
                                <?php } ?>                       
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<script src="/paa/static/departures/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="/paa/static/departures/vendor/bootstrap/js/popper.js"></script>
	<script src="/paa/static/departures/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="/paa/static/departures/vendor/select2/select2.min.js"></script>
    <script src="/paa/static/departures/js/main.js"></script>
    <script src="/paa/static/js/arrivals.js"></script>
    <button id="toggle">Fullscreen</button>
<script>
    if (document.fullscreenEnabled) {
        var btn = document.getElementById("toggle");
        btn.addEventListener("click", function (event) {
            if (!document.fullscreenElement) {
                btn.style = "display:none;";
                document.documentElement.requestFullscreen();
            } else {
                document.exitFullscreen();
            }
            
        }, false);
        
        document.addEventListener("fullscreenchange", function (event) { 
            console.log(event);
            
            if (!document.fullscreenElement) {
                btn.innerText = "Activate fullscreen";
            } else {
                btn.innerText = "Exit fullscreen";
            }
        });
        
        document.addEventListener("fullscreenerror", function (event) {   
            console.log(event); 
        });
    }
</script>
</body>
</html>