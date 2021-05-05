

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

            swalWithBootstrapButtons.fire('Processing,   Please wait.')
            swalWithBootstrapButtons.showLoading();

            let payload = JSON.stringify({
                destAcc:  $("#fund_account_account_number").val(),
                destBranch: $("#destBranch").val(),
                amount: amount,
                payDate: "2021-08-16",
                payCurrency: "ZMW",
                remarks: "",
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

