
function search_napsa_member() {
    let search_id = $("#napsa_search_id").val()

    if (search_id === ""){
        swal({
            title: "Error!",
            text: "ID Input must be populated",
            type: "error"
        })
    }else{
        $.ajax({
            method: 'post',
            url: '/btmms/api/napsa/member/search',
            dataType: 'json',
            contentType: 'application/json',
            success: function (response) {

                if(response.response.statusCode !== "SUCCESS"){
                    swal({
                        title: "Error!",
                        text: "Could not Connect to server",
                        type: "error"
                    });
                }else{
                    $("#napsa_member").val(search_id);
                    $("#new_user_redirect_form").submit();
                }

            },
            error: function (response){
                Swal.fire(
                    'Error!',
                    'Failed to Search Member!',
                    'error'
                )
            },
            data: JSON.stringify({id: search_id,})
        })
    }

}