
function user_validation(input){

    // console.log(input)
    // console.log(input.id)
    // console.log(input.name.replace("payload[", "").replace("]", ""))
    // console.log($("#"+input.id).val())

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
        data: JSON.stringify({column: input.name.replace("payload[", "").replace("]", ""), value: $("#"+input.id).val()})
    })

}

