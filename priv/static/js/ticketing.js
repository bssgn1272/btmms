function random_string(length) {
    var result           = '';
    var charz = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'
    var characters       = '0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function luggage_group_code() {
    let code = random_string(9)
    $("#unattended_luggage_code").html(code)
    return code
}

function checkinAction(){

    var ticketID = $("#checkin_ticket_id_1").val();
    console.log(ticketID);

    if (ticketID == "") {
        alert("Please add Ticket ID")
    } else {
        let json_request = JSON.stringify({
            payload: {
                ticket_id: parseInt(ticketID)
            }
        });

        $.ajax({
            method: 'post',
            url: '/api/v1/internal/tickets/find',
            dataType: 'json',
            contentType: 'application/json',
            data: json_request,
            success: function (response) {

                if (JSON.stringify(response) == "[]") {
                    $('#checkin_results_view').hide();
                    alert("No Ticket found for ticket " + ticketID);
                }else{
                    let data = JSON.parse(JSON.stringify(response));
                    console.log(data);
                    if(data.response.QUERY.data.activation_status == 'VALID'){
                        $("#checkin_ticket_id").html(data.response.QUERY.data.ticket_id);
                        $("#checkin_ticket_ref_number").html(data.response.QUERY.data.reference_number);
                        $("#checkin_ticket_serial_number").html(data.response.QUERY.data.serial_number);
                        $("#checkin_ticket_travel_date").html(data.response.QUERY.data.travel_date);
                        $("#checkin_ticket_name").html(data.response.QUERY.data.first_name + " " + data.response.QUERY.data.last_name);
                        $("#checkin_ticket_passenger_id").html(data.response.QUERY.data.id_number);
                        $("#checkin_ticket_cell").html(data.response.QUERY.data.mobile_number);

                        $('#checkin_results_view').show();
                    } else{
                        $('#checkin_results_view').hide();
                        alert("Ticket status: " + data.response.QUERY.data.activation_status)
                    }


                }
            }
        })
    }


}

function get_tarrif() {

    let json_request = JSON.stringify({
        tarrif_id: 1
    });

    var weight;
    $.ajax({
        method: 'post',
        url: '/api/v1/internal/get_luggage_tarrif',
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            let data = JSON.parse(JSON.stringify(response));
            weight = data.cost_per_kilo;
            console.log("weight 1:" + weight);
            return weight;
        }
    });
}

function commaSeperate(x) {
    var nf = new Intl.NumberFormat();
    return nf.format(x);
}

function get_weight() {

    let weight = "";
    $.ajax({
        method: 'get',
        url: '/api/v1/internal/scale/query',
        contentType: 'application/json',
        success: function (response) {
            weight = response;

            console.log("Response:" + response);

            $("#scale_weight_3").html(weight);
            $("#luggage_weight_2").html(weight);
            $("#luggage_ticket").html($("#checkin_ticket_id").html());

            let json_request = JSON.stringify({
                tarrif_id: 1
            });

            var weight2;
            $.ajax({
                method: 'post',
                url: '/api/v1/internal/get_luggage_tarrif',
                dataType: 'json',
                contentType: 'application/json',
                data: json_request,
                success: function (response) {
                    let data = JSON.parse(JSON.stringify(response));
                    weight2 = data.cost_per_kilo;
                    console.log("weight 1:" + weight);
                    var cost = (weight2 * parseFloat(weight)).toFixed(2);
                    $("#luggage_total_cost").html(commaSeperate(cost) );
                }
            });

        }
    });
}

function add_luggage_button() {

    //let w = $("#scale_weight").html();
    let lw = $("#scale_weight_3").html();
    let ltc = $("#luggage_total_cost").html();
    let dsc = $("#luggage_description").val();
    let ticket_interface_id2 = $("#checkin_ticket_id").html();

    let luggage_request = JSON.stringify({
        ticket_id: parseInt(ticket_interface_id2),
        description: dsc,
        weight: parseFloat(lw),
        cost: ltc
    });

    console.log("LV" + luggage_request);

    $.ajax({
        method: 'post',
        url: '/api/v1/internal/add_luggage',
        dataType: 'json',
        contentType: 'application/json',
        data: luggage_request,
        success: function (data_response) {
            let data2 = JSON.parse(JSON.stringify(data_response));
            console.log("add data" + data2);

            var ticket_interface_id = $("#checkin_ticket_id").html();
            let json_request = JSON.stringify({
                ticket_id: parseInt(ticket_interface_id)
            });

            $.ajax({
                method: 'post',
                url: '/api/v1/internal/get_luggage_by_ticket_id',
                dataType: 'json',
                contentType: 'application/json',
                data: json_request,
                success: function (response2) {
                    let data = JSON.parse(JSON.stringify(response2));
                    console.log(data);

                    let luggage_html_table = "<tr><th>Description</th><th>Ticket ID</th><th>Weight</th><th>Cost</th></tr>";

                    $.each(response2, function (k, v){
                        var obj = JSON.parse(JSON.stringify(v));
                        luggage_html_table += "<tr><td>"+obj.description+"</td><td>"+obj.ticket_id+"</td><td>"+obj.weight+"</td><td>"+obj.cost+"</td></tr>";
                    });

                    $("#luggage_table_list").html(luggage_html_table);

                }
            });
        }
    });


}

function get_unattended_luggage_weight() {

    let weight = "";
    $.ajax({
        method: 'get',
        url: '/api/v1/internal/scale/query',
        contentType: 'application/json',
        success: function (response) {
            weight = response;

            console.log("Response:" + response);

            $("#unattended_luggage_scale_weight").html(weight);
            $("#unattended_luggage_luggage_weight_2").html(weight);
            //$("#luggage_ticket").html($("#checkin_ticket_id").html());

            let json_request = JSON.stringify({
                tarrif_id: 1
            });

            var weight2;
            $.ajax({
                method: 'post',
                url: '/api/v1/internal/get_luggage_tarrif',
                dataType: 'json',
                contentType: 'application/json',
                data: json_request,
                success: function (response) {
                    let data = JSON.parse(JSON.stringify(response));
                    weight2 = data.cost_per_kilo;
                    console.log("weight 1:" + weight);
                    var cost = (weight2 * parseFloat(weight)).toFixed(2);
                    $("#unattended_luggage_luggage_total_cost").html(commaSeperate(cost) );
                }
            });

        }
    });
}

function add_unattended_luggage_button() {

    //let w = $("#scale_weight").html();
    let lw = $("#unattended_luggage_scale_weight").html();
    let ltc = $("#unattended_luggage_luggage_total_cost").html();
    let dsc = $("#unattended_luggage_luggage_description").val();
    let ticket_interface_id2 = $("#checkin_ticket_id").html();
    let luggage_tag_id = "1"

    let luggage_request = JSON.stringify({
        ticket_id: luggage_tag_id,
        description: dsc,
        weight: parseFloat(lw),
        cost: ltc
    });

    console.log("LV" + luggage_request);

    $.ajax({
        method: 'post',
        url: '/api/v1/internal/add_luggage',
        dataType: 'json',
        contentType: 'application/json',
        data: luggage_request,
        success: function (data_response) {
            let data2 = JSON.parse(JSON.stringify(data_response));
            console.log("add data" + data2);

            var ticket_interface_id = "1"//$("#checkin_ticket_id").html();
            let json_request = JSON.stringify({
                ticket_id: parseInt(ticket_interface_id)
            });

            $.ajax({
                method: 'post',
                url: '/api/v1/internal/get_luggage_by_ticket_id',
                dataType: 'json',
                contentType: 'application/json',
                data: json_request,
                success: function (response2) {
                    let data = JSON.parse(JSON.stringify(response2));
                    console.log(data);

                    let luggage_html_table = "<tr><th>Description</th><th>Ticket ID</th><th>Weight</th><th>Cost</th></tr>";

                    $.each(response2, function (k, v){
                        var obj = JSON.parse(JSON.stringify(v));
                        luggage_html_table += "<tr><td>"+obj.description+"</td><td>"+obj.ticket_id+"</td><td>"+obj.weight+"</td><td>"+obj.cost+"</td></tr>";
                    });

                    $("#unattended_luggage_table_list").html(luggage_html_table);

                }
            });
        }
    });


}

function addLuggageFunction() {
    $("#addLuggageModel").modal("show");
}

function checkInButton() {
    let ticket_id = $("#checkin_ticket_id").html();

    let checkin_request = JSON.stringify({
        ticket_id: parseInt(ticket_id),
    });

    $.ajax({
        method: 'post',
        url: '/api/v1/internal/checkin',
        dataType: 'json',
        contentType: 'application/json',
        data: checkin_request,
        success: function (data_response) {
            $('#checkin_results_view').hide();
            alert("Passenger Checked In")
        }
    });

}

$('#routes_dataTable').DataTable();
function toggle_route_search(){
    switch ($('#ticket_type').val()) {
        case "passenger_ticket":
            passenger_ticket_logic()
            break
        case "unattended_luggage":
            unattended_luggage_logic()
            break
    }
}

function unattended_luggage_logic() {
    $("#results_view").show();
    $("#unattended_view").show();
    $("#passenger_ticket_view").hide();
}

function passenger_ticket_logic() {
    $("#unattended_view").hide();

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();

    today = dd + "/" + mm + "/" + yyyy;

    let json_request = JSON.stringify({
        payload: {
            date: today,
            start_route: "Livingstone",
            end_route: $('#destination_option_select').val()
        }
    });
    let json_data = {};

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/travel/secured/internal/locations/destinations',
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            let data_object = JSON.parse(JSON.stringify(response));
            json_data = data_object;
            console.log(data_object);
            if (data_object.length < 1){
                $('#passenger_view').hide();
                $("#results_view").hide();
                $("#passenger_ticket_view").hide();
                alert("No Routes Found");
            } else {

                let trips_html = '';

                $.each(response, function (k,v) {
                    let single_object = JSON.parse(JSON.stringify(v));

                    let value = single_object.bus.company + "-" + single_object.route.start_route + "-"
                        + single_object.route.end_route + "-"  +  single_object.departure_time + "-" + single_object.fare;
                    value = value.toString();

                    //trips_html += '<div class="radio"><label><input type="radio" onclick="ticket_purchase(this.value)" value="'+value+'" name="opt_radio" />';
                    //trips_html += "\n" + value ;
                    //trips_html += '</label></div';
                    //trips_html += "\n";

                    trips_html += '<tr>' + '<th scope="row"><input type="radio" onclick="ticket_purchase(this.value)" value="'+value+'" name="opt_radio"></th>'+
                        '<td>' + single_object.bus.company +'</td>' + '<td>' + single_object.route.start_route + " -> "+
                        single_object.route.end_route +'</td>' + '<td>' + single_object.departure_time +'</td>' + '<td>' + single_object.fare
                        +'</td>' + '</tr>';
                });

                $("#results_view").show();
                $("#passenger_ticket_view").show();
                $('#trips_form').empty();
                $('#trips_form').html(trips_html);
            }
        }
    });
}