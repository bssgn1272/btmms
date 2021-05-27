

function fund_teller_modal(teller){
    console.log(teller)
    $("#fund_till_account_by_manager").modal("show");
    $("#teller_name").val(teller.first_name + " " + teller.last_name);
    $("#fund_account_account_number").val(teller.account_number);
    $("#destBranch").val(teller.bank_destBranch);
    $("#fund_account_till_balance").val(Math.round((teller.bank_account_balance + Number.EPSILON) * 100) / 100);
    $("#fund_account_wallet_balance").val(Math.round((teller.bank_account_balance + Number.EPSILON) * 100) / 100);

}

function fund_account(){
    let amount = $("#fund_amount").val();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You are about to transfer value to this account",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Transfer !',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            swalWithBootstrapButtons.fire('Funding Teller,   Please wait.')
            swalWithBootstrapButtons.showLoading();

            let payload = JSON.stringify({
                destAcc:  $("#fund_account_account_number").val(),
                destBranch: $("#destBranch").val(),
                amount: amount,
                payDate: "2021-08-16",
                payCurrency: "ZMW",
                remarks: "WALLET #NO (" + $("#fund_account_account_number").val() + ") FUNDED",
            });

            $.ajax({
                method: 'post',
                url: '/api/v1/internal/transaction/deposit',
                dataType: 'json',
                contentType: 'application/json',
                data: payload,
                success: function (response) {
                    if(response.status !== "SUCCESS"){
                        swalWithBootstrapButtons.close();
                        swal({
                            title: "Error!",
                            text: response.message,
                            type: "error"
                        },
                            function(){
                                location.reload();
                            });
                    }else{
                        swalWithBootstrapButtons.close();
                        swal({
                            title: "Completed!",
                            text: "Value transferred Successfully",
                            type: "success"
                        },
                            function(){
                                location.reload();
                            });
                    }
                }
            })

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Transfer Request Canceled',
                'error'
            )
        }
    })
}

function sweep_account(){

}

function fund_sweep(teller){
    console.log(teller)
    $("#sweep_till_account_by_manager").modal("show");

    $("#sweep_teller_name").val(teller.first_name + " " + teller.last_name);
    $("#sweep_account_account_number").val(teller.account_number);
    $("#sweep_srcBranch").val(teller.bank_srcBranch);
    $("#sweep_account_wallet_balance").val(Math.round((teller.bank_account_balance + Number.EPSILON) * 100) / 100);
}

function sweep_funds(){
    let amount = $("#sweep_account_wallet_balance").val();

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: true
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        text: "You are about to sweep value from this account",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Sweep!',
        cancelButtonText: 'No, cancel!',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            swalWithBootstrapButtons.fire('Sweeping to collections Account,   Please wait.')
            swalWithBootstrapButtons.showLoading();

            let payload = JSON.stringify({
                srcAcc:  $("#sweep_account_account_number").val(),
                srcBranch: $("#sweep_srcBranch").val(),
                amount: amount,
                payDate: "2021-08-16",
                payCurrency: "ZMW",
                remarks: "",
            });

            $.ajax({
                method: 'post',
                url: '/api/v1/internal/transaction/withdraw',
                dataType: 'json',
                contentType: 'application/json',
                data: payload,
                success: function (response) {
                    if(response.status !== "SUCCESS"){
                        swalWithBootstrapButtons.close();
                        swal({
                                title: "Error!",
                                text: "Failed to Transfer value",
                                type: "error"
                            },
                            function(){
                                location.reload();
                            });
                    }else{
                        swalWithBootstrapButtons.close();
                        swal({
                                title: "Completed!",
                                text: "Value transferred Successfully",
                                type: "success"
                            },
                            function(){
                                location.reload();
                            });
                    }
                }
            })

        } else if (
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire(
                'Cancelled',
                'Transfer Request Canceled',
                'error'
            )
        }
    })
}

function get_branches() {


    $.ajax({
        method: 'post',
        url: '/api/v1/internal/banks/get_bank_branches',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {
            console.log(response)

            let user_branches_html = '';

            $.each(response, function (v, o) {
                user_branches_html += '<option value="'+o+'">';
                user_branches_html += o;
                user_branches_html += '</option>';
            });

            $('#user_branches').html(user_branches_html);
        },
        data: JSON.stringify({bank: $("#user_bank").val()})
    })
}

function sortCode(){
    $.ajax({
        method: 'post',
        url: '/api/v1/internal/banks/get_bank',
        dataType: 'json',
        contentType: 'application/json',
        success: function (response) {
            console.log(response)
            let r = JSON.parse(JSON.stringify(response));
            console.log(r)
            $("#user_sortcode").val(r.sortCode);
        },
        data: JSON.stringify({bank: $("#user_bank").val(), branch: $("#user_branches").val()})
    })
}
