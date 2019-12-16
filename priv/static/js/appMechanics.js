$(document).ready( function () {
    $('#dataTableId').DataTable(); //User DataTable
    $('#dataTableBusTerminusId').DataTable(); //Bus Terminus DataTable
    $('#dataTableMarket').DataTable(); //Marktet DataTable
    $('#gates').DataTable(); //Gates DataTable
    $('#stations').DataTable(); //Stations DataTable
    $('#dataTableTellers').DataTable(); //Tellers DataTable
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