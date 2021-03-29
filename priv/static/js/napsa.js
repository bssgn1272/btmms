
function search_napsa_member() {
    let search_id = $("#napsa_search_id").val()

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    });

    swalWithBootstrapButtons.fire('Please wait')
    swalWithBootstrapButtons.showLoading();

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
                    swalWithBootstrapButtons.close();

                    swalWithBootstrapButtons.fire({
                        title: 'Search Failed',
                        text: "No Member with ID: " + search_id + " Found. Do you want to begin registration for this id?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Register',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // $("#regSystemUser").modal("hide");
                            $("#register_napsa_member").modal("show");

                        } else if (
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            // $("#regSystemUser").modal("hide");
                            swalWithBootstrapButtons.fire(
                                'Cancelled',
                                'Registration Canceled',
                                'error'
                            )
                        }
                    })
                }else{
                    $("#napsa_member").val(search_id);
                    $("#new_user_redirect_form").submit();
                }

            },
            error: function (response){
                swalWithBootstrapButtons.close();
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

function register_member() {

}

function confirm_registration() {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    });

    swalWithBootstrapButtons.fire({
        title: 'Member Registration',
        text: "Are you sure you want continue?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Continue',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            swalWithBootstrapButtons.fire('Registering ...')
            swalWithBootstrapButtons.showLoading();

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {

            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Registration Canceled',
                'error'
            )
        }
    })
}