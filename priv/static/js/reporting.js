
$("#reportByBusView").hide();
$("#reportByTellerView").hide();
$("#reportByOperatorView").hide();
$("#reportPassengerManifestView").hide();

function reportByBus() {
    $("#reportByBusView").show();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").hide();
    $("#reportTicketStatusManifestView").hide();
    $("#reportLuggageManifestView").hide();
}

function reportByTeller() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").show();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").hide();
    $("#reportTicketStatusManifestView").hide();
    $("#reportLuggageManifestView").hide();
}

function reportByOperator() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").show();
    $("#reportPassengerManifestView").hide();
    $("#reportTicketStatusManifestView").hide();
    $("#reportLuggageManifestView").hide();
}

function reportPassengerManifest() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").show();
    $("#reportTicketStatusManifestView").hide();
    $("#reportLuggageManifestView").hide();
}

function ticketStatus() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").hide();
    $("#reportTicketStatusManifestView").show();
    $("#reportLuggageManifestView").hide();
}

function luggageManifest() {
    $("#reportByBusView").hide();
    $("#reportByTellerView").hide();
    $("#reportByOperatorView").hide();
    $("#reportPassengerManifestView").hide();
    $("#reportTicketStatusManifestView").hide();
    $("#reportLuggageManifestView").show();
}