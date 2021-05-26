function CheckPassword() {
    console.log("checking")
    let inputtxt = $("#user_new_password").val();
    console.log(inputtxt)
    if (inputtxt.length >= 8){
        validatePassword(inputtxt)
    }

}

function validatePassword(inputtxt)
{
    var passw=  /^[A-Za-z]\w{6,9}$/;
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