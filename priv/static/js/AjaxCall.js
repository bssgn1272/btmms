$(document).ready(function() {

    $("#Operator_Name").change(function() {
        var val = $(this).val();
        $("#Depature_Time").html(options[val]);
    });


    var options = [
        "<option value='3:00AM'>3:00AM</option>, <option value='4:00AM'>4:00AM</option>, <option value='5:00AM'>5:00AM</option>",
        "<option value='6:00AM'>6:00AM</option>, <option value='6:30AM'>6:30AM</option>, <option value='7:00AM'>7:00AM</option>",
        "<option value='8:00AM'>8:00AM</option>, <option value='9:00AM'>9:00AM</option>, <option value='10:00AM'>10:00AM</option>",
        "<option value='14:00PM'>14:00PM</option>, <option value='15:00PM'>15:00PM</option>, <option value='17:00PM'>17:00PM</option>"
    ];

});  


/*$(function(){


//###### AJAX TO MAKE THE CALL
var $data = $('#data');

	$.ajax({
		type: 'GET',
		url: '/api/v1/btms/travel/secured/internal/destinations',
		success: function(data){
			$.each(data, function(i, data) {
				$data.append('<select>Results</select>');
			});
		}
	});
	

//JS TO PICK THE OBJECT
var Busdata = '{"bus":[' +
'{"company":"Mazhandu", "license_plate":"ABZ1243", "operator_id":"1"},' +
'{"company":"Euro", "license_plate":"AKJ234", "operator_id":"2"},' +
'{"company":"Juldan", "license_plate":"BAB707", "operator_id":"3"}]}';

var Routedata = '{"route":[' +
'{"end_route":"Lusaka", "route_code": "LSTL", "route_name": "LivingStone Lusaka", "source_state": "LIVING_STONE","start_route": "Living Stone"}]}';

//var jsonData = eval ("(" + data + ")");
var jsonData = JSON.parse(Busdata);
for (var i = 0; i < jsonData.bus.length; i++) {
    var counter = jsonData.bus[i];
    //console.log(counter.counter_name);
    alert(counter.company);
}
});*/



$("#date").change(function () {

    let date_entered = this.value.split("-").reverse().join("-");

    let json_request = JSON.stringify({
        payload: {
            date: date_entered.replace(/-/g,'/'),
            time: "09:00"
        }
    });


    $.ajax({
        method: 'post',
        url: '/api/v1/btms/travel/secured/internal/destinations',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {
            let data = JSON.stringify(response);
            if (data === JSON.stringify([])){
                alert("No Journey available on selected Day")
            }else{

                let bus_operator_html = '';
                let destination_to_html = '';
                let destination_from_html = '';

                $.each(response, function (k, v) {
                    let single_object = JSON.parse(JSON.stringify(v));

                    // Bus Operator
                    let operator_name = JSON.stringify(single_object.bus.company).replace(/"/g,'');
                    let departure_time = JSON.stringify(single_object.time).replace(/"/g,'');

                    bus_operator_html += '<option value=" Bus Operator">';
                    bus_operator_html += operator_name + ' - ' + departure_time;
                    bus_operator_html += '</option>';

                    // FROM Destination

                    destination_from_html += '<option value="Livingstone">';
                    destination_from_html += '</option>';

                    // TO Destination
                    let end_route = JSON.stringify(single_object.route.end_route);
                    destination_to_html += '<option value="">';
                    destination_to_html += end_route.replace(/"/g,'');
                    destination_to_html += '</option>';
                });

                $('#Operator_Name').html(bus_operator_html);
                //$('#Depature_Name').html(destination_from_html);
                $('#Destination_Name').html(destination_to_html);


                // let optionValues = [];
                // $('#Destination_Name option').each(function(){
                //     if($.inArray(this.value, optionValues) > -1){
                //         $(this).remove()
                //     }else{
                //         optionValues.push(this.value);
                //     }
                // });
            }
        },
        error: function (response){
          alert("Route Not Found On Selected Date")
        },
        data: json_request
    })
});
