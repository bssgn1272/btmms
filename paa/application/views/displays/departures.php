<!DOCTYPE html>
<html lang="en">
<head>
	<title>Departures</title>
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
        .Boarding {
            color: lime !important;
        }

        .Cancelled {
            color: red !important;
        }

        .Departed {
            color: orange !important;
        }
		
		.Delayed {
            color: lightcoral !important;
        }
    </style>
</head>
<body id="body" style="min-height:5000px;">
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
                            <h1 style="font-weight:bold;color:black;font-size:3.5em">DEPARTURES</h1>
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
                <div class="wrap-table100" id="top">
                    <div class="table100">
                        <table>
                            <thead>
                                <tr class="table100-head">
                                    <th class="">Operator</th>
                                    <!--th class="">No.</th-->
									<th class="">Destination</th>
                                    <th class="">Time</th>                               
                                    <th class="">Gate</th>
                                    <th class="">Status</th>
                                    <th class="">Seats Left</th>
                                </tr>
                            </thead>
                            <tbody id="tb">
                                <?php $i = 0; ?>
                                <?php foreach($departures as $departure){ ?>
                                <?php if($i >= 10){ break; } ?>
                                    <tr style="border:none !important;">
                                        <td class=""><?php echo $departure->company_name." (".$departure->bus_number.")"; ?></td>
                                        <td class=""><?php echo $departure->route_destination; ?></td>
                                        <td class=""><?php echo $departure->time; ?></td>
                                        <td class=""><center><?php echo $departure->bay_name; ?></center></td>
                                        <td class="<?php echo $departure->status_message; ?>"><?php echo $departure->status_message; ?></td>
                                        <td class=""><center><?php echo $departure->seats_available; ?></center></td>
                                    </tr>
									<tr>
										<td style="color:white !important;font-size:10px;">Stops:</td>
										<td colspan="5" style="color:white !important;font-size:10px;"><?php echo $departure->sub_routes; ?></td>
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
    <button id="toggle">Fullscreen</button>
	<button onclick="scrollWin()" id="btn">Scroll</button>
	<script src="/paa/static/departures/vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="/paa/static/departures/vendor/bootstrap/js/popper.js"></script>
	<script src="/paa/static/departures/vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="/paa/static/departures/vendor/select2/select2.min.js"></script>
    <script src="/paa/static/departures/js/main.js"></script>
    <script src="/paa/static/js/departures.js"></script>
<script>
	function scrollWin() {
	  window.scrollBy(0, 124);
	}

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
				btn.style = "display:block;";
            } else {
                btn.innerText = "Exit fullscreen";
				btn.style = "display:none;";
            }
        });
        
        document.addEventListener("fullscreenerror", function (event) {   
            console.log(event); 
        });
    }
</script>
</body>
</html>