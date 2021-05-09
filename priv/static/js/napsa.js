
function validate_national_id() {
    let national_id = $("#napsa_search_id").val().toString().trim();
    console.log(national_id)
    let id_split = national_id.split("")
    if ((national_id.length) >= 11 && id_split["6"] === '/' && id_split["9"] === '/'){
        search_napsa_member(national_id);
    } else if (national_id.length === 9 && id_split["6"] !== '/' && id_split["9"] !== '/'){
        search_napsa_member(national_id);
    }
}

function search_napsa_member(search_id) {
    // let search_id = $("#napsa_search_id").val()

    // let search_id = $("#napsa_search_id").val().toString().trim();
    // if ((search_id.length) >= 11){
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

                    console.log(response);

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
                                $("#member_national_id").val(search_id);

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
    // }

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

function add_beneficiary() {
    let national_id = $("#member_national_id").val();
    if (national_id.toString() === ""){
        Swal.fire(
            'Error!',
            'Please populate National ID field',
            'error'
        )
    }else{

        let json_request = JSON.stringify({
            member_id: national_id,
            beneficiary: {
                national_id: $('#member_bene_national_id').val(),
                first_name: $('#member_bene_first_name').val(),
                middle_name: $('#member_bene_middle_name').val(),
                last_name: $('#member_bene_last_name').val(),
                dob: $('#member_bene_dob').val(),
                gender: $('#member_bene_gender').val(),
                relationship_code: $('#member_bene_relationship_code').val(),
            }
        });

        $.ajax({
            method: 'post',
            url: '/api/v1/internal/napsa/add_member_beneficiary',
            dataType: 'json',
            contentType: 'application/json',
            success: function (response) {

                console.log(response)

                let beneficiary_list_html = '';
                let beneficiaries = response;
                for (let i = 0; i < beneficiaries.length; i++) {
                    beneficiary_list_html += '<option value="'+ beneficiaries[i].national_id +'">';
                    beneficiary_list_html += beneficiaries[i].first_name + ' ' + beneficiaries[i].last_name;
                    beneficiary_list_html += '</option>';
                }

                $('#member_bene_members').html(beneficiary_list_html);

            },
            data: json_request
        })
    }
}

function clear_beneficiaries() {

    let json_request = JSON.stringify({
        member_id: $("#member_national_id").val()
    });

    $.ajax({
        method: 'post',
        url: '/api/v1/internal/napsa/add_member_beneficiary',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {

            console.log(response)

            let beneficiary_list_html = '';
            let beneficiaries = response;
            for (let i = 0; i < beneficiaries.length; i++) {
                beneficiary_list_html += '<option value="'+ beneficiaries[i].national_id +'">';
                beneficiary_list_html += beneficiaries[i].first_name + ' ' + beneficiaries[i].last_name;
                beneficiary_list_html += '</option>';
            }

            $('#member_bene_members').html(beneficiary_list_html);

        },
        data: json_request
    })
}

function list_beneficiary() {
    let beneficiary_list_html = '';
    let beneficiaries = [];
    for (let i = 0; i < beneficiaries; i++) {
        beneficiary_list_html += '<option value="'+ beneficiaries[i].national_id +'">';
        beneficiary_list_html += beneficiaries[i].first_name + ' ' + beneficiaries[i].last_name;
        beneficiary_list_html += '</option>';
    }
}

function validate_member_bene_national_id() {
    let mid = $("#member_bene_national_id").val().toString().trim();
    if ((mid.length) >= 11){
        member_details(mid);
    }
}

function member_details(national_id) {

    // let national_id = $("#member_bene_national_id").val();
    console.log(national_id);

    $.ajax({
        method: 'post',
        url: '/btmms/api/napsa/member/search',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {

            console.log(response);

            if(response.response.statusCode === "SUCCESS"){
                $("#member_bene_first_name").val(response.response.payload.firstName);
                $("#member_bene_last_name").val(response.response.payload.lastName);
                $("#member_bene_dob").val(response.response.payload.dob);
            }

        },
        data: JSON.stringify({id: national_id,})
    })
}