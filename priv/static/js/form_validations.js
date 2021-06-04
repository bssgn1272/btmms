

function user_validation(input){

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    });

        let ivalue = $("#"+input.id).val()

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

function dobcheck()
{
    var birth = document.getElementById('spv_date')
    if(birth != "")
    {

        var record=document.getElementById('spv_date').value.trim();
        var currentdate3=new Date();
        var day1 = currentdate3.getDate();
        var month1 = currentdate3.getMonth();
        month1++;
        var year11 = currentdate3.getFullYear()-17;
        var year2= currentdate3.getFullYear()-100;
        var record_day1=record.split("/");
        var sum=record_day1[1]+'/'+record_day1[0]+'/'+record_day1[2];
        var current= month1+'/'+day1+'/'+year11;
        var current1= month1+'/'+day1+'/'+year2;
        var d1=new Date(current)
        var d2=new Date(current1)
        var record1 = new Date(sum);
        if(record1 > d1)
        {

            alert("Sorry ! Minors need parential guidance to use this website");
            document.getElementById('spv_date').blur();
            document.getElementById('spv_date').value="";
            document.getElementById('spv_date').focus();
            return false;
        }
    }
}

$(function(){
    var dtToday = new Date();

    var month = dtToday.getMonth() + 1;// jan=0; feb=1 .......
    var day = dtToday.getDate();
    var year = dtToday.getFullYear() - 16;
    var min_year = dtToday.getFullYear() - 65;
    if(month < 10)
        month = '0' + month.toString();
    if(day < 10)
        day = '0' + day.toString();
    var minDate = min_year + '-' + month + '-' + day;
    var maxDate = year + '-' + month + '-' + day;
    $('#spv_date').attr('max', maxDate);
    $('#spv_date').attr('min', minDate);
});
