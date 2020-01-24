$(document).ready( function () {
    $('#dataTableId').DataTable(); //User DataTable
    $('#dataTableBusTerminusId').DataTable(); //Bus Terminus DataTable
    $('#dataTableMarket').DataTable(); //Marktet DataTable
    $('#gates').DataTable(); //Gates DataTable
    $('#stations').DataTable(); //Stations DataTable
    $('#dataTableTellers').DataTable(); //Tellers DataTable
    $('#bookingsDataTable').DataTable(); //DataTable For Bookings
    $('#TellerPastTransactions').DataTable(); //DataTable For TellerPastTransactions
} );

$('#modal_form_horizontal_user').on('show.bs.model', function(e) {
    window.alert("Hello, World!");
    var parameters = row;
});

//-------------------New Bus Registration Actions --------------------
$('input#vehicleMake').keyup(function() {
    //perform ajax call...
    $('#vehicleMakeText').text($(this).val());
});

$('input#licensePlate').keyup(function() {
    //perform ajax call...
    $('#vehicleLicensePlateText').text($(this).val());
});

$('input#engineType').keyup(function() {
    //perform ajax call...
    $('#vehicleEngineTypeText').text($(this).val());
});

$('input#vehicleModel').keyup(function() {
    //perform ajax call...
    $('#vehicleModelText').text($(this).val());
});

$('input#vehicleYear').keyup(function() {
    //perform ajax call...
    $('#vehicleYearText').text($(this).val());
});

$('input#vehicleColor').keyup(function() {
    //perform ajax call...
    $('#vehicleColorText').text($(this).val());
});

$('input#stateOfRegistration').keyup(function() {
    //perform ajax call...
    $('#vehicleStateOfRegText').text($(this).val());
});

$('input#vinNumber').keyup(function() {
    //perform ajax call...
    $('#vehicleVinNumberText').text($(this).val());
});

$('input#vehicleHullNUmber').keyup(function() {
    //perform ajax call...
    $('#vehicleHullNumberText').text($(this).val());
});

$('input#vehicleSerialNumber').keyup(function() {
    //perform ajax call...
    $('#vehicleSerialNumberText').text($(this).val());
});

$('input#vehicleClass').keyup(function() {
    //perform ajax call...
    $('#vehicleClassText').text($(this).val());
});

$('input#vehicleCompany').keyup(function() {
    //perform ajax call...
    $('#vehicleCompanyText').text($(this).val());
});

//-------------------------------------------------------------------

//-------------------Teller Actions --------------------
$('input#TfirstName').keyup(function() {
    //perform ajax call...
    $('#TellerFirstName').text($(this).val());
});

$('input#TlastName').keyup(function() {
    //perform ajax call...
    $('#TellerLastName').text($(this).val());
});

$('input#Tsex').keyup(function() {
    //perform ajax call...
    $('#TellerSex').text($(this).val());
});

$('input#Tdob').keyup(function() {
    //perform ajax call...
    $('#TellerDoB').text($(this).val());
});

$('input#Tnrc').keyup(function() {
    //perform ajax call...
    $('#TellerNRC').text($(this).val());
});

$('input#Tssn').keyup(function() {
    //perform ajax call...
    $('#TellerSSN').text($(this).val());
});

$('input#Temail').keyup(function() {
    //perform ajax call...
    $('#TellerEmail').text($(this).val());
});

$('input#Tphone').keyup(function() {
    //perform ajax call...
    $('#TellerPhone').text($(this).val());
});

$('input#Taddress').keyup(function() {
    //perform ajax call...
    $('#TellerAddress').text($(this).val());
});
//-------------------------------------------------------------------


//------------------- Billing --------------------
$('input#cardNumber').keyup(function() {
    //perform ajax call...
    $('#CardNUMBER').text($(this).val());
});

$('input#securityCode').keyup(function() {
    //perform ajax call...
    $('#SecurityCODE').text($(this).val());
});
//------------------------------------------------


//-------------------Payments --------------------
$('input#CARDNUBER').keyup(function() {
    //perform ajax call...
    $('#cardnuber').text($(this).val());
});

$('input#SECURITYCODE').keyup(function() {
    //perform ajax call...
    $('#securitycode').text($(this).val());
});
$('input#PHONE').keyup(function() {
    //perform ajax call...
    $('#phone').text($(this).val());
    $('#phone2').text($(this).val());
});

$('input#FNAME').keyup(function() {
    //perform ajax call...
    $('#fname').text($(this).val());
});

$('input#LNAME').keyup(function() {
    //perform ajax call...
    $('#lname').text($(this).val());
});

$('input#ADDRESS').keyup(function() {
    //perform ajax call...
    $('#address').text($(this).val());
});

$('input#TOWN').keyup(function() {
    //perform ajax call...
    $('#town').text($(this).val());
});

$('input#EMAIL').keyup(function() {
    //perform ajax call...
    $('#email').text($(this).val());
});

//------------------------------------------------
//-------------------Ticket Payments --------------------
$('input#First_Name').keyup(function() {
    //perform ajax call...
    $('#Pname').text($(this).val());
    $('#PFname').text($(this).val());
    $("input#first_name_input").val($(this).val());
});

$('input#Last_Name').keyup(function() {
    //perform ajax call...
    $('#Lname').text($(this).val());
    $('#PLname').text($(this).val());
    $('input#last_name_input').val($(this).val());
});


$('input#date').keyup(function() {
    //perform ajax call...
    $('#dateOfDepature').text($(this).val());
    $('#PdateOfDepature').text($(this).val());
    $('input#travel_date_input').val($(this).val());
});
$('input#Passenger_Number').keyup(function() {
    //perform ajax call...
    $('#Ppassengernumber').text($(this).val());
});
$('input#Contact_Number').keyup(function() {
    //perform ajax call...
    $('#PContatct').text($(this).val());
    $('input#mobile_number_input').val($(this).val());
});

$('input#Depature_Time').keyup(function() {
    //perform ajax call...
    $('#Time').text($(this).val());
    $('#PTime').text($(this).val());
    $('input#travel_date_input').val($(this).val());
});


$('input#Depature_Name').keyup(function() {
    //perform ajax call...
    $('#From').text($(this).val());
    $('#PFrom').text($(this).val());
});

$('input#Destination_Name').keyup(function() {
    //perform ajax call...
    $('#To').text($(this).val());
    $('#PTo').text($(this).val());
});

$('input#Ticket_Number').keyup(function() {
    //perform ajax call...
    $('#ticketNeumber').text($(this).val());
});

$('input#Ref_Number').keyup(function() {
    //perform ajax call...
    $('#RefNo').text($(this).val());
});

$('input#seat').keyup(function() {
    //perform ajax call...
    $('#SeatPosition').text($(this).val());
    $('#PSeatPosition').text($(this).val());
});

//------------------------------------------------

//---------------DropDowns----------------------
function GetSelectedText() {
    var x = document.getElementById("Operator_Name").value;
    document.getElementById("result").innerHTML = x;
  }

function GetSelectedTo() {
  var x = document.getElementById("Destination_Name").value;
  document.getElementById("To").innerHTML = x;
}

function GetSelectedTime() {
  var x = document.getElementById("Depature_Time").value;
  document.getElementById("Time").innerHTML = x;
}

function GetSelectedFrom() {
  var x = document.getElementById("Depature_Name").value;
  document.getElementById("From").innerHTML = x;
}

function GetSelectedDate() {
  var x = document.getElementById("date").value;
  document.getElementById("dateOfDepature").innerHTML = x;
}

function GetSelectedSeat() {
  var x = document.getElementById("Position").value;
  document.getElementById("SeatPosition").innerHTML = x;
}



/*
    function GetSelectedValue(){
                var e = document.getElementById("Operator_Name");
                var result = e.options[e.selectedIndex].value;
                
                document.getElementById("result").innerHTML = result;
            }

            function GetSelectedText(){
                var e = document.getElementById("Operator_Name");
                var result = e.options[e.selectedIndex].text;
                
                document.getElementById("result").onload=function() {GetSelectedText()};
            }
            */


//---------------DropDowns----------------------
