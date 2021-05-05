let process_url = '/api/btmms/service/till/management';

$(document).ready(function () {
    $('#busTerminusDT tbody').on('click', '.enable_teller_user', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Enable Teller!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Enable!'
        }).then((result) => {
            if (result.value) {
                let data = JSON.stringify({
                    sigma: $(this).attr('data-form'),
                    view: "",
                    sendId: $(this).attr('data-id'),
                    username: $(this).attr('data-username')
                });
                $.ajax({
                    method: 'POST',
                    url: process_url,
                    dataType: 'json',
                    contentType: 'application/json',
                    data: data,
                    success: function (response) {
                        if (response.status === 0) {
                            Swal.fire(
                                'Enbled Teller Successfully!',
                                response.message,
                                'success'
                            ).then((result) => {
                                location.reload(true);
                            });
                        } else {
                            Swal.fire(
                                'Enbling Teller Failed!',
                                response.message,
                                'error'
                            )
                        }
                    }
                });
            } else {
                Swal.fire(
                    'Cancelled!',
                    'Your data has not being submitted :)',
                    'error'
                )
            }
        });
    });
    $('#busTerminusDT tbody').on('click', '.disable_teller_user', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Disable Teller!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Disable!'
        }).then((result) => {
            if (result.value) {
                let data = JSON.stringify({
                    sigma: $(this).attr('data-form'),
                    view: "",
                    sendId: $(this).attr('data-id'),
                    username: $(this).attr('data-username')
                });
                $.ajax({
                    method: 'POST',
                    url: process_url,
                    dataType: 'json',
                    contentType: 'application/json',
                    data: data,
                    success: function (response) {
                        if (response.status === 0) {
                            Swal.fire(
                                'Disable Teller Successfully!',
                                response.message,
                                'success'
                            ).then((result) => {
                                location.reload(true);
                            });
                        } else {
                            Swal.fire(
                                'Disabling Teller Failed!',
                                response.message,
                                'error'
                            )
                        }
                    }
                });
            } else {
                Swal.fire(
                    'Cancelled!',
                    'Your data has not being submitted :)',
                    'error'
                )
            }
        });
    });
    $('#busTerminusDT tbody').on('click', '.fund_swept_user', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to Sweep Teller Account!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sweep!'
        }).then((result) => {
            if (result.value) {
                let data = JSON.stringify({
                    sigma: $(this).attr('data-form'),
                    view: "",
                    sendId: $(this).attr('data-id')
                });
                $.ajax({
                    method: 'POST',
                    url: process_url,
                    dataType: 'json',
                    contentType: 'application/json',
                    data: data,
                    success: function (response) {
                        if (response.status === 0) {
                            Swal.fire(
                                'Sweep Teller Successfully!',
                                response.message,
                                'success'
                            ).then((result) => {
                                location.reload(true);
                            });
                        } else {
                            Swal.fire(
                                'Sweep Teller Failed!',
                                response.message,
                                'error'
                            )
                        }
                    }
                });
            } else {
                Swal.fire(
                    'Cancelled!',
                    'Your data has not being submitted :)',
                    'error'
                )
            }
        });
    });
    $('#busTerminusDT tbody').on('click', '.fund_teller_user', function (e) {
        e.preventDefault();
        let data = JSON.stringify({
            sigma: $(this).attr('data-form'),
            view: "",
            sendId: $(this).attr('data-id')
        });
        $.ajax({
            method: 'POST',
            url: process_url,
            dataType: 'json',
            contentType: 'application/json',
            data: data,
            success: function (response) {
                if (response.status === 0) {
                    let detail = response.details
                    console.log(response)
                    $('#fund_account_name').val(detail.name)
                    $('#fund_account_account_number').val(detail.account)
                    $('#fund_account_till_balance').val(detail.till_balance)
                    $('#fund_account_wallet_balance').val(detail.wallet_balance)
                    $('#fund_till_account_by_manager').modal('show');
                } else {
                    Swal.fire(
                        'Fetch Failed!',
                        response.message,
                        'error'
                    )
                }
            }
        });
    });
    $('#fund_teller_post').submit(function(e) {
        e.preventDefault();
        let data = $(this).serialize();
        $('#fund_till_account_by_manager').modal('hide');
        Swal.fire({
            title: 'Are you sure?',
            text: 'You will Fund teller on Till',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    method: 'POST',
                    url: process_url,
                    data: data,
                    success: function (response) {
                        if (response.status === 0) {
                            Swal.fire(
                                'Funded Successfully!',
                                response.message,
                                'success'
                            ).then((result) => {
                                location.reload(true);
                            });
                        } else {
                            Swal.fire(
                                'Failed!',
                                response.message,
                                'error'
                            )
                        }
                    }
                });
            } else {
                Swal.fire(
                    'Cancelled!',
                    'Your imaginary file is safe :)',
                    'error'
                )
            }
        });
    });
})