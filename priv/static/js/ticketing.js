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

function cancel_ticket(ticket_id) {

    // $("#cancel_button").onclick(function () {
    //     console.log("canceled")
    // })

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You are about to cancel this ticket!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Cancel it!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                method: 'post',
                url: '/api/v1/internal/tickets/cancel',
                dataType: 'json',
                contentType: 'application/json',
                success: function (response) {

                    if(response.status !== "SUCCESS"){
                        swal({
                            title: "Error!",
                            text: "Could not Connect to server",
                            type: "error"
                        });
                    }else{
                        swal({
                            title: "Completed!",
                            text: "Ticket Canceled Successfully",
                            type: "success"
                        }, function(){
                                window.location.href = "/platform/secure/commercial/services/users/management"
                            });
                    }
                },
                error: function (response){
                    Swal.fire(
                        'Error!',
                        'Failed to Cancel Ticket!',
                        'error'
                    )
                },
                data: JSON.stringify({ticket_id: ticket_id,})
            })

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Request Canceled',
                'error'
            )
        }
    })
}

function checkinAction(){
    $('#checkin_results_view').hide();
    var ticketID = $("#checkin_ticket_id_1").val();


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
                    if(data.response.QUERY.data.activation_status === 'VALID' || data.response.QUERY.data.activation_status === 'TRANSFER'){
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

            $("#scale_weight_3").html(weight + " Kg");
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
                    $("#luggage_item_cost").html(commaSeperate(cost) );
                }
            });

        }
    });
}
let v_ticket = ""
function add_luggage_button() {

    //let w = $("#scale_weight").html();
    let lw = $("#scale_weight_3").html();
    let ltc = $("#luggage_item_cost").html();
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

            let ticket_interface_id = $("#checkin_ticket_id").html();

            if(ticket_interface_id === ""){
                ticket_interface_id = "0"
            }

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

                    $.ajax({
                        method: 'post',
                        url: '/api/v1/internal/get_luggage_by_ticket_id_total_cost',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: json_request,
                        success: function (total_cost_res) {
                            $("#luggage_total_cost").html(total_cost_res);
                        }
                    });

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

function virtual_ticket(){


    let travel_source ="Livingstone"
    let travel_destination = $("#destination_option_select").val();

    let virtual_ticket_request = JSON.stringify({
        source: travel_source,
        destination: travel_destination
    });

    $.ajax({
        method: 'post',
        url: '/api/v1/internal/create/virtual_ticket',
        dataType: 'json',
        contentType: 'application/json',
        data: virtual_ticket_request,
        success: function (v_ticket_response) {
            let ticket = JSON.parse(JSON.stringify(v_ticket_response));
            console.log("ticket: " + ticket)
            v_ticket = ticket.id.toString()
            $("#unattended_luggage_ticket_id_input").val(v_ticket);
            $("#Luggage_id_tag").html(v_ticket);
        }
    })
}

function acquire_luggage(){
    let acquire_request = JSON.stringify({
        receiver: $("#unattended_receiver_luggage_phone_input").val(),
        sender: $("#unattended_sender_luggage_phone_input").val(),
        luggage_id: v_ticket
    });

    $.ajax({
        method: 'post',
        url: '/api/v1/internal/create/acquire_luggage',
        dataType: 'json',
        contentType: 'application/json',
        data: acquire_request,
        success: function (v_ticket_response) {
            window.location.reload()
        }
    })
}

function add_unattended_luggage_button() {

    if (v_ticket === ""){
        virtual_ticket()
    }else{
        //let w = $("#scale_weight").html();

        let lw = $("#unattended_luggage_scale_weight").html();
        let ltc = $("#unattended_luggage_luggage_total_cost").html();
        let dsc = $("#unattended_luggage_luggage_description").val();
        let ticket_interface_id2 = $("#checkin_ticket_id").html();
        let luggage_tag_id = v_ticket.toString()

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
                console.log("add data: " + data2);

                var ticket_interface_id = v_ticket.toString() //$("#checkin_ticket_id").html();
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

}

function reload_page() {
    location.reload();
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

    $('#passenger_view').hide();
    $("#unattended_layout_view").hide();

    switch ($('#ticket_type').val()) {
        case "passenger_ticket":
            passenger_ticket_logic()
            break
        case "unattended_luggage":
            unattended_luggage_logic()
            break
    }
}

let ticket_to_transfer;
function transfer_ticket(ticket) {

    console.log(ticket)

    if (ticket.activation_status === "BOARDED"){
        swal({
            title: "Transfer Failed!",
            text: "Can not transfer a ticket that has already been boarded",
            type: "error"
        }, function(){
            // window.location.href = "/platform/secure/commercial/services/users/management"
        });
    }else{
        selected_ticket = ticket
        ticket_to_transfer = ticket

        let today = new Date();
        let dd = String(today.getDate()).padStart(2, '0');
        let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        let yyyy = today.getFullYear();

        today = dd + "/" + mm + "/" + yyyy;

        $.ajax({
            method: 'get',
            url: '/api/v1/btms/travel/secured/routes',
            dataType: 'json',
            contentType: 'application/json',
            success: function (response) {

                let data = JSON.parse(JSON.stringify(response));
                var list = distinct_destination(data.travel_routes);

                options = list;
                $('#transfer_destination_option_select').empty();
                $.each(options, function(i, p) {
                    $('#transfer_destination_option_select').append($('<option></option>').val(p).html(p));
                });

                $('#transferModal').modal("show");
            }
        })
    }
}


function transfer_ticket_logic(){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();

    today = dd + "/" + mm + "/" + yyyy;

    let json_request = JSON.stringify({
        payload: {
            date: "",
            start_route: "Livingstone",
            end_route: $('#transfer_destination_option_select').val()
        }
    });
    let json_data = {};

    swalWithBootstrapButtons.fire('Please wait')
    swalWithBootstrapButtons.showLoading();

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/travel/secured/internal/locations/destinations/internal',
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            let data_object = JSON.parse(JSON.stringify(response));
            json_data = data_object;
            console.log(data_object);
            if (data_object.length < 1){

                swalWithBootstrapButtons.close();

                swalWithBootstrapButtons.fire(
                    'No Route(s) Found',
                    'Route(S) could not be found for Livingstone to ' + $('#transfer_destination_option_select').val(),
                    'error'
                )
                $('#transfer_trips_form').empty();
            } else {
                swalWithBootstrapButtons.close();
                let trips_html = '';

                $.each(response, function (k,v) {
                    let single_object = JSON.parse(JSON.stringify(v));



                    let value = single_object.bus.company.trim().split(" ").join("").toString() + "-" + single_object.route.start_route + "-"
                        + single_object.route.end_route + "-"  +  single_object.departure_time + "-" + single_object.fare + "-" + single_object.bus.id + "-"
                        + single_object.slot + "-" + single_object.bus_schedule_id + "-" + single_object.discount_amount + "-" + single_object.discount_status + "-"
                        + single_object.bus.id + "-" + single_object.root_route.id;
                    value = value.toString();

                    let bus_schedule_id = single_object.bus_schedule_id
                    bus_schedule_id = bus_schedule_id.toString();

                    date_obj = single_object.departure_date.split("T")[0]
                    date_arr = date_obj.split("-")
                    date = date_arr[2] + "/" + date_arr[1] + "/" + date_arr[0]


                    trips_html += '<tr>' + '<th scope="row"><input type="radio" onclick="ticket_transfer(this.value, ticket_to_transfer)" value="'+value+'" name="opt_radio"></th>'+
                        '<td>' + single_object.bus.company +'</td>' + '<td>' + single_object.route.start_route + " -> "+
                        single_object.route.end_route +'</td>' + '<td>' + single_object.departure_time +'</td>' + '<td>' + date +'</td>' + '<td>' + single_object.available_seats +'</td>' + '<td>' + single_object.fare
                        +'</td>' + '</tr>';
                });

                $('#transfer_trips_form').empty();
                $('#transfer_trips_form').html(trips_html);
            }
        }
    });
}

function ticket_transfer(route, ticket) {
    console.log(route)
    console.log(ticket)

    let rd = route.split(/-/g);

    let info = "OPERATOR: " + rd[0] + "\t START: " + rd[1] + "\t END: " + rd[2] + "\t DEPARTURE: " + rd[3] + "\t PRICE: K" + rd[4] + "\t GATE: " + rd[6] + "\t SCHEDULE: " + rd[7];



    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You are about to transfer this ticket from [Livingstone -> " + ticket.end_route + "]" + " to [Livingstone -> " + rd[2] + "]",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Transfer !',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {

        console.log(rd[4].toString())
        console.log(ticket.amount.toString())
        if (rd[4].toString() !== ticket.amount.toString()){
            swal({
                title: "Transfer Failed!",
                text: "Amount is not valid for transfer, Please cancel and rebook new ticket with new amount",
                type: "error"
            }, function(){
                window.location.href = "/platform/secure/commercial/services/users/management"
            });
        }else {

            swalWithBootstrapButtons.fire('Please wait')
            swalWithBootstrapButtons.showLoading();

            if (result.isConfirmed) {

                let payload = JSON.stringify({
                    ticket: {
                        id: ticket.id
                    },
                    params: {
                        route_information: info,
                        activation_status: "TRANSFER",
                        start_route: rd[1],
                        end_route: rd[2],
                        bus_no: rd[10],
                        route: rd[11],
                        date: rd[3],
                        bus_schedule_id: rd[7],
                        ticket_description: "Ticket transferred from (Livingstone to " + ticket.end_route + ") to " + "(Livingstone to " + rd[2] + ") at " + new Date().toLocaleString().replace(",","").replace(/:.. /," ")
                    }
                });

                $.ajax({
                    method: 'post',
                    url: '/api/v1/internal/tickets/update',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: payload,
                    success: function (response) {
                        if(response.status !== "SUCCESS"){
                            swalWithBootstrapButtons.close();
                            swal({
                                title: "Error!",
                                text: "Failed to Transfer Ticket",
                                type: "error"
                            }, function(){
                                window.location.href = "/platform/secure/commercial/services/users/management"
                            });
                        }else{
                            $('#transferModal').modal("hide");
                            swalWithBootstrapButtons.close();
                            swal({
                                title: "Completed!",
                                text: "Ticket Transferred Successfully",
                                type: "success"
                            }, function(){
                                window.location.href = "/platform/secure/commercial/services/users/management"
                            });
                        }
                    }
                })

            } else if (
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Ticket Transfer Canceled',
                    'error'
                )
            }
        }

    })
}

function unattended_luggage_logic() {
    $("#results_view").show();
    $("#unattended_view").show();

    passenger_ticket_logic();
}

$("#unattended_layout_view").hide();
function passenger_ticket_logic() {
    $("#unattended_view").hide();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();

    today = dd + "/" + mm + "/" + yyyy;

    let json_request = JSON.stringify({
        payload: {
            date: "",
            start_route: "Livingstone",
            end_route: $('#destination_option_select').val()
        }
    });
    let json_data = {};

    swalWithBootstrapButtons.fire('Please wait')
    swalWithBootstrapButtons.showLoading();

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/travel/secured/internal/locations/destinations/internal',
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        timeout: 4000, // sets timeout to 3 seconds
        success: function (response) {
            let data_object = JSON.parse(JSON.stringify(response));
            json_data = data_object;
            console.log(data_object);
            console.log("response");
            if (data_object.length < 1){
                $('#passenger_view').hide();
                $("#results_view").hide();
                $("#passenger_ticket_view").hide();

                swalWithBootstrapButtons.close();

                swalWithBootstrapButtons.fire(
                    'No Route(s) Found',
                    'Route(S) could not be found for Livingstone to ' + $('#destination_option_select').val(),
                    'error'
                )
            } else {

                swalWithBootstrapButtons.close();

                let trips_html = '';

                $.each(response, function (k,v) {
                    let single_object = JSON.parse(JSON.stringify(v));

                    console.log(single_object)
                    console.log(single_object.departure_date.split("T")[0].replaceAll("-","/"))

                    let value = single_object.bus.company.trim().split(" ").join("_").toString() + "-" + single_object.route.start_route + "-"
                        + single_object.route.end_route + "-"  +  single_object.departure_time + "-" + single_object.fare + "-" + single_object.bus.id + "-"
                        + single_object.slot + "-" + single_object.bus_schedule_id + "-" + single_object.discount_amount + "-" + single_object.discount_status + "-" + single_object.departure_date.split("T")[0].replaceAll("-","/");
                    value = value.toString();

                    //trips_html += '<div class="radio"><label><input type="radio" onclick="ticket_purchase(this.value)" value="'+value+'" name="opt_radio" />';
                    //trips_html += "\n" + value ;
                    //trips_html += '</label></div';
                    //trips_html += "\n";

                    date_obj = single_object.departure_date.split("T")[0]
                    date_arr = date_obj.split("-")
                    date = date_arr[2] + "/" + date_arr[1] + "/" + date_arr[0]


                    trips_html += '<tr>' + '<th scope="row"><input type="radio" onclick="ticket_purchase(this.value)" value="'+value+'" name="opt_radio"></th>'+
                        '<td>' + single_object.bus.company +'</td>' + '<td>' + single_object.route.start_route + " -> "+
                        single_object.route.end_route +'</td>' + '<td>' + single_object.departure_time +'</td>' + '<td>' + date +'</td>' + '<td>' + single_object.available_seats +'</td>' + '<td>' + single_object.fare
                        +'</td>' + '</tr>';
                });

                $("#results_view").show();
                $("#passenger_ticket_view").show();
                $('#trips_form').empty();
                $('#trips_form').html(trips_html);
            }
        }, error: function(){
            swalWithBootstrapButtons.close();
            swal({title: "Error", text: "An Internal Error Occurred, could not fetch routes. Please contact Administrator", type: "error"},
                function(){

                }
            );
        }
    });
}

function ticket_purchase(value){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    console.log(value.split(/-/g));
    let rd = value.split(/-/g);
    console.log("RD")
    console.log(rd)

    switch ($('#ticket_type').val()) {

        case "passenger_ticket":

            if (rd[8] >= rd[4] && rd[9] === "true"){
                swalWithBootstrapButtons.fire(
                    'Invalid Route',
                    'Discount Amount Higher than Fare',
                    'error'
                )
            }else{
                $('#passenger_view').show();
                $("#results_view").hide();
                let info = "OPERATOR: " + rd[0] + "\t START: " + rd[1] + "\t END: " + rd[2] + "\t DEPARTURE: " + rd[3] + "\t PRICE: K" + rd[4] + "\t GATE: " + rd[6] + "\t SCHEDULE: " + rd[7];
                $('#route_information').val(info);

                $('#operator_Model').val(rd[0]);
                $('#start_Model').val(rd[1]);
                $('#end_Model').val(rd[2]);
                $('#time_Model').val(rd[3]);
                $('#departure_date_Model').val(rd[10]);

                $('#bus_id_input').val(rd[5]);
            }

            break
        case "unattended_luggage":
            $('#passenger_view').hide();
            $("#unattended_layout_view").show();

            $("#unattended_luggage_operator").val(rd[0]);
            $("#unattended_luggage_source").val(rd[1]);
            $("#unattended_luggage_destination").val(rd[2]);
            $("#unattended_luggage_start_route").val(rd[1]);
            $("#unattended_luggage_end_route").val(rd[2]);
            $("#unattended_luggage_departure_date").val(rd[3]);
            $("#unattended_luggage_amount").val(rd[4]);
            $("#unattended_luggage_bus_id").val(rd[5]);
            $("#unattended_luggage_gate").val(rd[6]);
            $("#unattended_luggage_bus_schedule_id").val(rd[7]);

            break
    }
}

function ticket_confirmation() {
    $("#First_Name_Model").val($("#First_Name").val());
    $("#Last_Name_Model").val($("#Last_Name").val());
    $("#Contact_Number_Model").val($("#Contact_Number").val());
    $("#Id_Type_Model").val($("#id_type2").val());
    $("#ID_Model").val($("#ID").val());
    $("#route_information_Model").val($("#route_information").val());
}

function purchase_ticket_internal() {

    let json_request = JSON.stringify({
        payload: {
            first_name: $('#First_Name_Model').val(),
            session_user_id: $('#session_user_id').val(),
            reference_number: $('#reference_number_Model').val(),
            external_ref: $('#external_ref_Model').val(),
            serial_number: $('#serial_number_Model').val(),
            activation_status: $('#activation_status_Model').val(),
            id_type: $('#id_type_Model').val(),
            maker: $('#maker_Model').val(),
            bus_no: $('#bus_id_input').val(),
            transaction_channel: $('#transaction_channel_Model').val(),
            payment_mode: $('#payment_mode_Model').val(),
            travel_date: $('#travel_date_Model').val(),
            last_name: $('#Last_Name_Model').val(),
            mobile_number: $('#Contact_Number_Model').val(),
            passenger_id: $('#ID_Model').val(),
            route_information: $('#route_information_Model').val(),
            date: $('#time_Model').val(),
            departure_date: $('#departure_date_Model').val(),
            ticket_description: "Ticket Purchased from (Livingstone to " + $('#end_Model').val() + ")"

        }
    });

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    swalWithBootstrapButtons.fire('Processing,   Please wait.')
    swalWithBootstrapButtons.showLoading();

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/plvPM5f+H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hICn',
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (data_response) {
            // let single_object = JSON.parse(data_response);
            console.log(data_response)
            let single_object = JSON.parse(data_response.ticket);
            console.log(single_object)
            $('#modal_theme_primary').modal("hide");
            $('#passenger_view').hide();
            $("#results_view").hide();
            $("#passenger_ticket_view").hide();

            let status = single_object.status
            console.log(single_object)
            if(data_response.status === 200){
                swalWithBootstrapButtons.close();

                document.getElementById("bank_account_balance").innerHTML = "K" + Math.round((data_response.bank_account_balance + Number.EPSILON) * 100) / 100;

                ticket_back_to_routes2();

                swal({title: "Purchase Complete", text: "Ticket Purchase Successful! ID: " + single_object.id, type: "success"},
                    function(){
                        ticket_back_to_routes2();
                    }
                );
            }else{
                swalWithBootstrapButtons.close();

                document.getElementById("bank_account_balance").innerHTML = "K" + Math.round((data_response.bank_account_balance + Number.EPSILON) * 100) / 100;

                ticket_back_to_routes2();

                swal({title: "Purchase Failed", text: data_response.message, type: "error"},
                    function(){
                        ticket_back_to_routes2();
                    }
                );

            }
        }, error: function(){
            swalWithBootstrapButtons.close();
            swal({title: "Error", text: "An Internal Error Occurred, could not fetch routes. Please contact Administrator", type: "error"},
                function(){

                }
            );
        }
    });
    // method="post" action="<%= Routes.ticket_path(@conn, :create) %>"
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

let selected_ticket;
function reschedule_ticket(ticket) {
    console.log(ticket)
    $('#rescheduleModal').modal("show");

     selected_ticket = ticket

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();

    today = dd + "/" + mm + "/" + yyyy;

    let json_request = JSON.stringify({
        payload: {
            date: "",
            start_route: "Livingstone",
            end_route: ticket.end_route
        }
    });
    let json_data = {};

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/travel/secured/internal/locations/destinations/internal',
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            let data_object = JSON.parse(JSON.stringify(response));
            json_data = data_object;
            console.log(data_object);
            if (data_object.length < 1){
                alert("No Routes Found");
            } else {

                let trips_html = '';

                $.each(response, function (k,v) {
                    let single_object = JSON.parse(JSON.stringify(v));

                    console.log(single_object)

                    let value = single_object.bus.company.trim().split(" ").join("_").toString() + "-" + single_object.route.start_route + "-"
                        + single_object.route.end_route + "-"  +  single_object.departure_time + "-" + single_object.fare + "-" + single_object.bus.id + "-"
                        + single_object.slot + "-" + single_object.bus_schedule_id;
                    value = value.toString();

                    //trips_html += '<div class="radio"><label><input type="radio" onclick="ticket_purchase(this.value)" value="'+value+'" name="opt_radio" />';
                    //trips_html += "\n" + value ;
                    //trips_html += '</label></div';
                    //trips_html += "\n";

                    date_obj = single_object.departure_date.split("T")[0]
                    date_arr = date_obj.split("-")
                    date = date_arr[2] + "/" + date_arr[1] + "/" + date_arr[0]


                    trips_html += '<tr>' + '<th scope="row"><input type="radio" onclick="reschedule_logic(this.value, selected_ticket)" value="'+value+'" name="opt_radio"></th>'+
                        '<td>' + single_object.bus.company +'</td>' + '<td>' + single_object.route.start_route + " -> "+
                        single_object.route.end_route +'</td>' + '<td>' + single_object.departure_time +'</td>' + '<td>' + date +'</td>' + '<td>' + single_object.available_seats +'</td>' + '<td>' + single_object.fare
                        +'</td>' + '</tr>';
                });

                // $("#results_view").show();
                // $("#passenger_ticket_view").show();
                // $('#trips_form').empty();
                $('#reschedule_trips_form').html(trips_html);
            }
        }, error: function(){

            swal({title: "Error", text: "An Internal Error Occurred, could not fetch routes. Please contact Administrator", type: "error"},
                function(){

                }
            );
        }
    });
}

function reschedule_logic(value, ticket) {
    console.log(value)
    console.log(ticket)

    console.log(value.split(/-/g));
    let rd = value.split(/-/g);

    let info = "OPERATOR: " + rd[0] + "\t START: " + rd[1] + "\t END: " + rd[2] + "\t DEPARTURE: " + rd[3] + "\t PRICE: K" + rd[4] + "\t GATE: " + rd[6] + "\t SCHEDULE: " + rd[7];
    console.log(info)

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You are about to reschedule this ticket!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Reschedule !',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            let payload = JSON.stringify({
                ticket: {
                    id: ticket.id
                },
                params: {
                    route_information: info,
                    activation_status: "VALID",
                    start_route: rd[1],
                    end_route: rd[2]
                }
            });

            $.ajax({
                method: 'post',
                url: '/api/v1/internal/tickets/update',
                dataType: 'json',
                contentType: 'application/json',
                data: payload,
                success: function (response) {
                    if(response.status !== "SUCCESS"){
                        swal({
                            title: "Error!",
                            text: "Failed to Reschedule Ticket",
                            type: "error"
                        }, function(){
                            window.location.href = "/platform/secure/commercial/services/users/management"
                        });
                    }else{
                        swal({
                            title: "Completed!",
                            text: "Ticket Rescheduled Successfully",
                            type: "success"
                        }, function(){
                            window.location.href = "/platform/secure/commercial/services/users/management"
                        });
                    }
                }
            })

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Reschedule Request Canceled',
                'error'
            )
        }
    })
}

function ticket_back_to_routes(){

    $("#First_Name").val("");
    $("#Last_Name").val("");
    $("#Contact_Number").val("");
    $("#id_type2").val("");
    $("#ID").val("");
    $("#route_information").val("");

    $('#passenger_view').hide();
    $("#results_view").show();
}

function ticket_back_to_routes2(){

    $("#First_Name").val("");
    $("#Last_Name").val("");
    $("#Contact_Number").val("");
    $("#id_type2").val("");
    $("#ID").val("");
    $("#route_information").val("");

    $('#passenger_view').hide();
    $("#results_view").hide();
}