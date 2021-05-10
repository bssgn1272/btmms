
let MARKET_URL = '/api/v1/internal/markets';

let MODEL_USER;

function pull_market_list (id) {

    $('#stand_allocate_button').hide();
    $('#market_section_div').hide();
    $('#market_stand_div').hide();

    MODEL_USER = id;
    //$('#model_name_username').text("Name: " + username);

    let json_request = JSON.stringify({
        branch: "MARKET",
        action: "LIST",
        module: "market",
        use_params: false,
        params: "[]"
    });

    $.ajax({
        method: 'post',
        url: MARKET_URL,
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            console.log(response);

            let market_html = '';

            $.each(response, function (k, v) {
                let o = JSON.parse(JSON.stringify(v));
                market_html += '<option value="'+o.id+'">';
                market_html += o.id + " - " + o.market_name;
                market_html += '</option>';
            });

            $('#market_list').html(market_html);
            $('#market_model_list').html(market_html);
        }
    });
}

function pull_section_list () {

    let json_request = JSON.stringify({
        branch: "MARKET",
        action: "LIST",
        module: "section",
        use_params: false,
        params: "[]"
    });

    $.ajax({
        method: 'post',
        url: MARKET_URL,
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            console.log(response);

            let section_html = '';

            $.each(response, function (k, v) {
                let o = JSON.parse(JSON.stringify(v));
                section_html += '<option value="'+o.id+'">';
                section_html += o.id + " - " + o.section_name + "(" + o.section_lable + ")";
                section_html += '</option>';
            });

            $('#section_list').html(section_html);
            $('#market_section_model_list').html(section_html);
        }
    });
}

function pull_shop_list () {

    let json_request = JSON.stringify({
        branch: "MARKET",
        action: "LIST",
        module: "shop",
        use_params: false,
        params: "[]"
    });

    $.ajax({
        method: 'post',
        url: MARKET_URL,
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            console.log(response);

            let shop_html = '';

            $.each(response, function (k, v) {
                let o = JSON.parse(JSON.stringify(v));
                shop_html += '<option value="'+o.id+'">';
                shop_html += o.id + " - " + o.shop_code;
                shop_html += '</option>';
            });

            $('#market_shop_model_list').html(shop_html);
        }
    });
}

function pull_section_list_by_market_id () {

    let json_request = JSON.stringify({
        branch: "MARKET",
        action: "LIST",
        module: "section",
        use_params: true,
        params: "["+$('#market_model_list').val().toString()+"]"
    });

    $.ajax({
        method: 'post',
        url: MARKET_URL,
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            console.log(response);

            let section_html = '';

            if (response.length < 1){
                $('#market_section_div').hide();
            }else{
                $('#market_section_div').show();
                $.each(response, function (k, v) {
                    let o = JSON.parse(JSON.stringify(v));
                    section_html += '<option value="'+o.id+'">';
                    section_html += o.id + " - " + o.section_name + " (" + o.section_lable + ")";
                    section_html += '</option>';
                });

                $('#section_list').html(section_html);
                $('#market_section_model_list').html(section_html);
            }


        }
    });
}

function pull_stand_list_by_section_id () {

    let json_request = JSON.stringify({
        branch: "MARKET",
        action: "QUERY",
        module: "section",
        use_params: true,
        params: "["+$('#market_section_model_list').val().toString()+"]"
    });

    $.ajax({
        method: 'post',
        url: MARKET_URL,
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {

            let shop_html = '';

            if (response.number_of_shops < 1){
                $('#stand_allocate_button').hide();
                $('#market_stand_div').hide();
                shop_html += '<option value="'+0+'">';
                shop_html += " No Stands Available ";
                shop_html += '</option>';

                $('#market_stand_model_list').html(shop_html);
            }else{
                $('#market_stand_div').show();
                $('#stand_allocate_button').show();

                let json_stand_request = JSON.stringify({
                    branch: "MARKET",
                    action: "LIST",
                    module: "stand",
                    use_params: true,
                    params: "["+$('#market_section_model_list').val().toString()+"]"
                });

                $.ajax({
                    method: 'post',
                    url: MARKET_URL,
                    dataType: 'json',
                    contentType: 'application/json',
                    data: json_stand_request,
                    success: function (stand_response) {


                        let recoded_stands = [];
                        for (let i = 0; i < response.number_of_shops ; i++) {
                            recoded_stands[i] = i+1
                        }

                        let database_stands = [];
                        for (let i = 0; i < stand_response.length ; i++) {
                            database_stands[i] = stand_response[i].shop_number;
                        }

                        let available_stands = recoded_stands.filter(function(x) {
                            return database_stands.indexOf(x) < 0;
                        });


                        for (let i = 0; i < available_stands.length; i++) {
                            shop_html += '<option value="'+ available_stands[i] +'">';
                            shop_html += "Stand: " + available_stands[i];
                            shop_html += '</option>';
                        }

                        $('#market_stand_model_list').html(shop_html);
                    }
                });


            }

        }
    });
}

function allocate_stand() {
    let json_request = JSON.stringify({
        user_id: MODEL_USER
    });

    $.ajax({
        method: 'post',
        url: MARKET_URL,
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            // console.log($('#market_list').val());
            // console.log($('#market_section_model_list').val());
            // console.log($('#market_stand_model_list').val());
            // console.log(response);

            let json_stand_allocation_request = JSON.stringify({
                branch: "MARKET",
                action: "CREATE",
                module: "stand",
                use_params: true,
                params: {
                    shop_code: new Date().getTime().toString(),
                    section_id: $('#market_section_model_list').val(),
                    maketeer_id: MODEL_USER,
                    shop_number: $('#market_stand_model_list').val(),
                    shop_price: $('#stand_price').val()
                }
            });

            $.ajax({
                method: 'post',
                url: MARKET_URL,
                dataType: 'json',
                contentType: 'application/json',
                data: json_stand_allocation_request,
                success: function (response) {
                    window.location.reload()
                }
            });
        }
    });
}

function pull_section_stand_count_by_market_id () {

    let json_request = JSON.stringify({
        branch: "MARKET",
        action: "LIST",
        module: "section",
        use_params: true,
        params: "["+$('#market_section_model_list').val().toString()+"]"
    });

    $.ajax({
        method: 'post',
        url: MARKET_URL,
        dataType: 'json',
        contentType: 'application/json',
        data: json_request,
        success: function (response) {
            console.log(response);

            let section_html = '';

            if (response.length < 1){
                $('#market_section_div').hide();
            }else{
                $('#market_section_div').show();
                $.each(response, function (k, v) {
                    let o = JSON.parse(JSON.stringify(v));
                    section_html += '<option value="'+o.id+'">';
                    section_html += o.id + "  " + o.section_name + "(" + o.section_lable + ")";
                    section_html += '</option>';
                });

                $('#section_list').html(section_html);
                $('#market_section_model_list').html(section_html);
            }


        }
    });
}