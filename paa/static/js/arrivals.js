window.onload = function(){
	//window.location = "javascript:window.scrollBy(0,124);
    r = 0;
    stillProcessing = false;
    
    function updateRows(){
        if(!stillProcessing){
            var xmlhttp = new XMLHttpRequest();
            var url = "/paa/displays/arrivals_json";

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

    async function updateTable(arrivals){
        var rows = 10;
        var displays = Math.ceil(arrivals.length/rows);
        var index = 0;

        stillProcessing = true;
        for(i = 0; i < displays; i++){
            var out = "";
            for(j = 0; j < rows; j++){
                try{
                    out += '<tr><td>' + arrivals[index].company_name + ' (' + arrivals[index].bus_number + ')' + '</td><td>' + arrivals[index].route_destination + '</td><td>' + arrivals[index].time + '</td><td><center>' + arrivals[index].bay_name + '</center></td><td class="' + arrivals[index].status_message + '">' + arrivals[index].status_message + '</td>';
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