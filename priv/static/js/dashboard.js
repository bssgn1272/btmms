
function admin_container() {
    $('#dashboard_container').show();
    $('#ticket_container').style.display = "none";
    let user_role = $('#dashboard_container_id').val();
    if (user_role === "SADMIN"){
        $('#ticket_container').hide()
    }
}

function ticketing_container() {
    $('#dashboard_container').hide();
    $('#ticket_container').show();
    console.log("Called");
    let user_role = $('#ticket_container_id').val();
    console.log(user_role);
    if (user_role === "SADMIN"){
        $('#ticket_container').show();
        console.log("Showing");
    }

}