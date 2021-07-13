
function change_user_password(){
    // document.getElementById('reset_form_id').submit();
    console.log($('#message').html());
    if ($("#reset_current_password").val() !== "" && $("#password").val() !== "" && $("#confirm_password").val() !== ""){
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: true
        });

        if($('#message').html() === "Not Matching"){
            swalWithBootstrapButtons.fire(
                'Passwords do not match',
                'Please re-type your passwords and ensure they are the same',
                'error'
            )
        }else{
            var passw=  /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
            if($("#confirm_password").val().match(passw))
            {
                $.ajax(
                    {
                    method: 'post',
                    url: '/api/v1/btms/H5TWgFg8ovMeZFZqKEdqXfetZ7LsytqO5Oilh8vHuiRnyqd1uWE6hIC0',
                    dataType: 'json',
                    contentType: 'application/json',
                    success: function (response) {

                        console.log(response)

                        if (response.status === 0) {
                            // swalWithBootstrapButtons.fire(
                            //     'Success',
                            //     response.message,
                            //     'success'
                            // )

                            swal({title: "Password Update Successful", text:  response.message, type: "success"},
                                function(){
                                    window.location.href = "/logout"
                                }
                            );
                        }else{
                            swalWithBootstrapButtons.fire(
                                'Request Failed',
                                response.message,
                                'error'
                            )
                        }


                    },
                    data: JSON.stringify({username: $("#model_pwd_username").val(), password: $("#reset_current_password").val(), new_password: $("#confirm_password").val()})
                })
            }
            else
            {
                swalWithBootstrapButtons.fire(
                    'Password Policy Error',
                    'Must contain at least one number one uppercase and lowercase letter, and at least 8 or more characters',
                    'error'
                )

            }

        }


    }
}

$('#password, #confirm_password').on('keyup', function () {
    if ($('#password').val() == $('#confirm_password').val()) {
        $('#message').html('Matching').css('color', 'green');
    } else
        $('#message').html('Not Matching').css('color', 'red');
});

$('#pwd1, #pwd2').on('keyup', function () {
    if ($('#pwd1').val() == $('#pwd2').val()) {
        $('#message').html('Matching').css('color', 'green');
    } else
        $('#message').html('Not Matching').css('color', 'red');
});

function fta_update_password(){

    console.log($('#message').html());


    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    let pwd1 = $("#pwd1").val()
    let pwd2 = $("#pwd2").val()
    let username = $("#username").val()
    console.log(pwd1)
    if (pwd1.toString() === "" || pwd2 === "") {
        swal({title: "Blank Field", text: "Please populate both fields before continuing", type: "error"}
        );
    }else{

        if ($("#pwd1").val() !== "" && $("#pwd2").val() !== ""){
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true
            });

            if($('#message').html() === "Not Matching"){
                swalWithBootstrapButtons.fire(
                    'Passwords do not match',
                    'Please re-type your passwords and ensure they are the same',
                    'error'
                )
            }else{
                //$("#pwd2").val().match(passw)
                var passw=  /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
                if($("#pwd2").val().match(passw))
                {
                    swalWithBootstrapButtons.fire('Updating your password, Please wait...')
                    swalWithBootstrapButtons.showLoading();

                    let json_request = JSON.stringify({
                        username: username,
                        password: pwd1,
                        confirm_password: pwd2
                    });

                    $.ajax({
                        method: 'post',
                        url: '/api/v1/btms/secured/password/fta_update',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: json_request,
                        timeout: 3000, // sets timeout to 3 seconds
                        success: function (response) {
                            if (response.status === 200){
                                swalWithBootstrapButtons.close();
                                swal({title: "Password Update Successful", text: "Your Password has been updated", type: "success"},
                                    function(){
                                        back_to_login();
                                    }
                                );
                            }else{
                                swalWithBootstrapButtons.close();
                                swal({title: "Password Update Failed", text: response.message, type: "error"},
                                    function(){
                                        // back_to_login();
                                    }
                                );
                            }
                        },
                        error: function(){
                            swalWithBootstrapButtons.close();
                            swal({title: "Error", text: "An Error Occurred and could not reset password", type: "error"},
                                function(){

                                }
                            );
                        }
                    });
                }
                else
                {
                    swalWithBootstrapButtons.fire(
                        'Password Policy Error',
                        'Must contain at least one number one uppercase and lowercase letter, and at least 8 or more characters',
                        'error'
                    )

                }
            }
        }

        //--------------------------------------------

    }
}

function password_reset(){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    let username = $("#reset_username").val()
    console.log(username)
    if (username.toString() === "") {
        swal({title: "Invalid Username", text: "Please enter a valid username before proceeding", type: "error"}
        );
    }else{
        swalWithBootstrapButtons.fire('Resetting your password, Please wait...')
        swalWithBootstrapButtons.showLoading();

        let json_request = JSON.stringify({
            username: username
        });

        $.ajax({
            method: 'post',
            url: '/api/v1/btms/secured/password/reset',
            dataType: 'json',
            contentType: 'application/json',
            data: json_request,
            timeout: 3000, // sets timeout to 3 seconds
            success: function (response) {
                if (response.status === 200){
                    swalWithBootstrapButtons.close();
                    swal({title: "Password Reset Successful", text: "Your Password has been reset and sms sent", type: "success"},
                        function(){
                            back_to_login();
                        }
                    );
                }else{
                    swalWithBootstrapButtons.close();
                    swal({title: "Password Reset Failed", text: response.message, type: "error"},
                        function(){
                            // back_to_login();
                        }
                    );
                }
            },
            error: function(){
                swalWithBootstrapButtons.close();
                swal({title: "Error", text: "An Error Occurred and could not reset password", type: "error"},
                    function(){

                    }
                );
            }
        });

    }
}

function back_to_login() {
    window.location.href = "/"
}

function logout(){
    window.location.href = "/"
}