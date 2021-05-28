function CheckPassword() {
    console.log("checking")
    let inputtxt = $("#user_new_password").val();
    console.log(inputtxt)
    if (inputtxt.length >= 8){
        validatePassword(inputtxt)
    }

}

function change_password() {
    $("#change_password_modal").modal("show");
}

function validatePassword(inputtxt)
{
    var passw=  /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/;
    if(inputtxt.value.match(passw))
    {
        alert('Correct, try another...')
        return true;
    }
    else
    {
        alert('Wrong...!')
        return false;
    }
}