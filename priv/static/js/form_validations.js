
function user_validation(input){

    let ivalue = $("#"+input.id).val()

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    });

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hICn',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {

            if (response.exist > 0) {
                $("#"+input.id).val("");
                swalWithBootstrapButtons.fire(
                    'Error',
                    'A User with ' + input.id + ': ' + ivalue + ' Already Exists',
                    'error'
                )
            }


        },
        data: JSON.stringify({table: "probase_tbl_users", column: input.name.replace("payload[", "").replace("]", ""), value: $("#"+input.id).val()})
    })

}


function bus_validation(input){

    let ivalue = $("#"+input.id).val()

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    });

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hICn',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {

            if (response.exist > 0) {
                $("#"+input.id).val("");
                swalWithBootstrapButtons.fire(
                    'Error',
                    'A Bus with ' + input.id + ': ' + ivalue + ' Already Exists',
                    'error'
                )
            }


        },
        data: JSON.stringify({table: "probase_tbl_bus", column: input.name.replace("payload[", "").replace("]", ""), value: $("#"+input.id).val()})
    })

}


function route_validation(input){

    let ivalue = $("#"+input.id).val()

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    });

    $.ajax({
        method: 'post',
        url: '/api/v1/btms/H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hICn',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {

            if (response.exist > 0) {
                $("#"+input.id).val("");
                swalWithBootstrapButtons.fire(
                    'Error',
                    'A Route with ' + input.id + ': ' + ivalue + ' Already Exists',
                    'error'
                )
            }


        },
        data: JSON.stringify({table: "probase_tbl_travel_routes", column: input.name.replace("payload[", "").replace("]", ""), value: $("#"+input.id).val()})
    })

}

