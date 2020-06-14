<!DOCTYPE html>
<html lang="en">
<head>
	<title>Departures</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="/paa/static/departures/image/png" href="images/icons/favicon.ico"/>
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/vendor/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/css/util.css">
	<link rel="stylesheet" type="text/css" href="/paa/static/departures/css/main.css">
</head>
<body id="body">
    <div>
        <div class="row">
            <div class="container-table100">
                <span class="'table100-head">
                    <div class="row">
                        <div class="'col-md-4">
                            <h1 style="font-weight:bold;color:white;float:right !important;">
                                <span id="date">00-00-00</span>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </h1>
                        </div>
                        <div class="'col-md-4">
                            <h1 style="font-weight:bold;color:white;">DEPARTURES</h1>
                        </div>
                        <div class="'col-md-4">
                            <h1 style="font-weight:bold;color:white;float:right !important;">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span  id="time">00:00:00</span>
                            </h1>
                        </div>
                    </div>
                </span>
                <script>
                    setInterval(function(){
                        var today = new Date();
                        var hours = '00';
                        var minutes = '00';
                        var seconds = '00';
                        if(today.getHours() < 10){
                            hours = '0' + today.getHours();
                        }
                        else{
                            hours = today.getHours();
                        }
                        if(today.getMinutes() < 10){
                            minutes = '0' + today.getMinutes();
                        }
                        else{
                            minutes = today.getMinutes();
                        }
                        if(today.getSeconds() < 10){
                            seconds = '0' + today.getSeconds();
                        }
                        else{
                            seconds = today.getSeconds();
                        }
                        var time = hours + ":" + minutes + ":" + seconds;
                        document.getElementById("time").innerHTML = time;

                        var month = '00';
                        var day = '00';
                        if((today.getMonth()+1) < 10){
                            month = '0' + (today.getMonth()+1);
                        }
                        else{
                            month = (today.getMonth()+1);
                        }
                        if((today.getDate()+1) < 10){
                            day = '0' + today.getDate();
                        }
                        else{
                            day = today.getDate();
                        }
                        var date = today.getFullYear()+'-'+month+'-'+day;
                        document.getElementById("date").innerHTML = date;
                    }, 1000);
                </script>
                <div class="wrap-table100">
                    <div class="table100">
                        <table>
                            <thead>
                                <tr class="table100-head">
                                    <th class="">Bus Operator</th>
                                    <th class="">No.</th>
                                    <th class="">Time</th>
                                    <th class="">To</th>
                                    <th class="">Bay</th>
                                    <th class="">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php foreach($departures as $departure){ ?>
                                <?php if($i >= 10){ break; } ?>
                                    <tr>
                                        <td class=""><?php echo $departure->company_name; ?></td>
                                        <td class=""><?php echo $departure->bus_number; ?></td>
                                        <td class=""><?php echo $departure->time; ?></td>
                                        <td class=""><?php echo $departure->route_destination; ?></td>
                                        
                                        <td class=""><?php echo $departure->bay_name; ?></td>
                                        <td class=""><?php echo $departure->status_message; ?></td>
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