
$("#reportByBusView").hide();
$("#reportByTellerView").hide();
$("#reportByOperatorView").hide();
$("#reportPassengerManifestView").hide();

function reportByBus() {
    $("#reportByBusView").show();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").hide();
}

function reportByTeller() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").show();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").hide();
}

function reportByOperator() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").show();
    $("#reportPassengerManifestView").hide();
}

function reportPassengerManifest() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").show();
}