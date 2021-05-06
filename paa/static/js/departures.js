window.onload = function(){
	//document.getElementById("btn").click();
	//window.location = "javascript:window.scrollBy(0,124)";
	
	setTimeout(function() {
		scrollPos = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
		if (scrollPos < 1) {
		  window.scrollTo(0,124);
		}
	  }, 0);
	  
    r = 0;
    stillProcessing = false;
    
    function updateRows(){
        if(!stillProcessing){
            var xmlhttp = new XMLHttpRequest();
            var url = "/paa/displays/departures_json";

            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var depatures = JSON.parse(this.responseText);
                    updateTable(depatures);
                }
            };
            console.log(url);
            xmlhttp.open("GET", url, true);
            xmlhttp.send();
        }
    }

    function sleep(ms){
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function updateTable(departures){
        var rows = 10;
        var displays = Math.ceil(departures.length/rows);
        var index = 0;

        stillProcessing = true;
        for(i = 0; i < displays; i++){
            var out = "";
            for(j = 0; j < rows; j++){
                try{
                    out += '<tr style="border:none !important;margin:0 !important;padding:0 !important;"><td>' + departures[index].company_name + ' (' + departures[index].bus_number + ')' + '</td><td>' + departures[index].route_destination + '</td><td>' + departures[index].time + '</td><td><center>' + departures[index].bay_name + '</center></td><td class="' + departures[index].status_message + '">' + departures[index].status_message + '</td><td><center>' + departures[index].seats_available + '</center></td></tr><tr style="margin:0 !important;padding:0 !important;"><td style="color:white !important;font-size:10px;">Stops:</td><td colspan="5" style="color:white !important;font-size:10px;">' + departures[index].sub_routes + '</td></tr>';
                    index++;
                }
                catch(err){
                    break;
                }
            }
            document.getElementById("page").innerHTML = (i + 1) + '/' + displays;
            document.getElementById("tb").innerHTML = out;
            await sleep(10000);
        }
        stillProcessing = false;
    }

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
        updateRows();
    }, 1000);
};