window.onload = function () {
// ############## MOST-USED-BUSES ##############
var chart = new CanvasJS.Chart("chartContainer4", {
    animationEnabled: true,
    title: {
        text: "Most Used Bus Operators"
    },
    data: [{
        type: "pie",
        startAngle: 240,
        yValueFormatString: "##0.00\"%\"",
        indexLabel: "{label} {y}",
        dataPoints: [
            {y: 79.45, label: "Mazhandu Family Bus"},
            {y: 7.31, label: "J C K Transport LTD"},
            {y: 7.06, label: "Wada Chovu Bus Services LTD"},
            {y: 4.91, label: "Juldan Motors"},
            {y: 1.26, label: "Others"}
        ]
    }]
});
chart.render();
// ############## MOST-USED-BUSES ##############


// ############## MOST-USED-ROUTES ##############
    var chart = new CanvasJS.Chart("chartContainer3", {
    animationEnabled: true,
    theme: "light2",
    title:{
        text: "Most Used Routes"
    },
    axisX:{
        valueFormatString: "DD MMM",
        crosshair: {
            enabled: true,
            snapToDataPoint: true
        }
    },
    axisY: {
        title: "Number of Visits",
        crosshair: {
            enabled: true
        }
    },
    toolTip:{
        shared:true
    },  
    legend:{
        cursor:"pointer",
        verticalAlign: "bottom",
        horizontalAlign: "left",
        dockInsidePlotArea: true,
        itemclick: toogleDataSeries
    },
    data: [{
        type: "line",
        showInLegend: true,
        name: "Total Visit",
        markerType: "square",
        xValueFormatString: "DD MMM, YYYY",
        color: "#F08080",
        dataPoints: [
            { x: new Date(2017, 0, 3), y: 650 },
            { x: new Date(2017, 0, 4), y: 700 },
            { x: new Date(2017, 0, 5), y: 710 },
            { x: new Date(2017, 0, 6), y: 658 },
            { x: new Date(2017, 0, 7), y: 734 },
            { x: new Date(2017, 0, 8), y: 963 },
            { x: new Date(2017, 0, 9), y: 847 },
            { x: new Date(2017, 0, 10), y: 853 },
            { x: new Date(2017, 0, 11), y: 869 },
            { x: new Date(2017, 0, 12), y: 943 },
            { x: new Date(2017, 0, 13), y: 970 },
            { x: new Date(2017, 0, 14), y: 869 },
            { x: new Date(2017, 0, 15), y: 890 },
            { x: new Date(2017, 0, 16), y: 930 }
        ]
    },
    {
        type: "line",
        showInLegend: true,
        name: "Unique Visit",
        lineDashType: "dash",
        dataPoints: [
            { x: new Date(2017, 0, 3), y: 510 },
            { x: new Date(2017, 0, 4), y: 560 },
            { x: new Date(2017, 0, 5), y: 540 },
            { x: new Date(2017, 0, 6), y: 558 },
            { x: new Date(2017, 0, 7), y: 544 },
            { x: new Date(2017, 0, 8), y: 693 },
            { x: new Date(2017, 0, 9), y: 657 },
            { x: new Date(2017, 0, 10), y: 663 },
            { x: new Date(2017, 0, 11), y: 639 },
            { x: new Date(2017, 0, 12), y: 673 },
            { x: new Date(2017, 0, 13), y: 660 },
            { x: new Date(2017, 0, 14), y: 562 },
            { x: new Date(2017, 0, 15), y: 643 },
            { x: new Date(2017, 0, 16), y: 570 }
        ]
    }]
});
chart.render();

function toogleDataSeries(e){
    if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    } else{
        e.dataSeries.visible = true;
    }
    chart.render();
}
// ############## MOST-USED-ROUTES ##############

//############## ONLINE TRANSACTIONS ##############
var chart = new CanvasJS.Chart("chartContainer2", {
    animationEnabled: true,
    theme: "light2",
    title: {
        text: "Online Transactions"
    },
    axisX: {
        valueFormatString: "MMM"
    },
    axisY: {
        prefix: "ZMW",
        labelFormatter: addSymbols
    },
    toolTip: {
        shared: true
    },
    legend: {
        cursor: "pointer",
        itemclick: toggleDataSeries
    },
    data: [
    {
        type: "column",
        name: "Actual Sales",
        showInLegend: true,
        xValueFormatString: "MMMM YYYY",
        yValueFormatString: "ZMW#,##0",
        dataPoints: [
            { x: new Date(2016, 0), y: 20000 },
            { x: new Date(2016, 1), y: 30000 },
            { x: new Date(2016, 2), y: 25000 },
            { x: new Date(2016, 3), y: 70000, indexLabel: "High Renewals" },
            { x: new Date(2016, 4), y: 50000 },
            { x: new Date(2016, 5), y: 35000 },
            { x: new Date(2016, 6), y: 30000 },
            { x: new Date(2016, 7), y: 43000 },
            { x: new Date(2016, 8), y: 35000 },
            { x: new Date(2016, 9), y:  30000},
            { x: new Date(2016, 10), y: 40000 },
            { x: new Date(2016, 11), y: 50000 }
        ]
    }, 
    {
        type: "line",
        name: "Expected Sales",
        showInLegend: true,
        yValueFormatString: "ZMW#,##0",
        dataPoints: [
            { x: new Date(2016, 0), y: 40000 },
            { x: new Date(2016, 1), y: 42000 },
            { x: new Date(2016, 2), y: 45000 },
            { x: new Date(2016, 3), y: 45000 },
            { x: new Date(2016, 4), y: 47000 },
            { x: new Date(2016, 5), y: 43000 },
            { x: new Date(2016, 6), y: 42000 },
            { x: new Date(2016, 7), y: 43000 },
            { x: new Date(2016, 8), y: 41000 },
            { x: new Date(2016, 9), y: 45000 },
            { x: new Date(2016, 10), y: 42000 },
            { x: new Date(2016, 11), y: 50000 }
        ]
    },
    {
        type: "area",
        name: "Profit",
        markerBorderColor: "white",
        markerBorderThickness: 2,
        showInLegend: true,
        yValueFormatString: "ZMW#,##0",
        dataPoints: [
            { x: new Date(2016, 0), y: 5000 },
            { x: new Date(2016, 1), y: 7000 },
            { x: new Date(2016, 2), y: 6000},
            { x: new Date(2016, 3), y: 30000 },
            { x: new Date(2016, 4), y: 20000 },
            { x: new Date(2016, 5), y: 15000 },
            { x: new Date(2016, 6), y: 13000 },
            { x: new Date(2016, 7), y: 20000 },
            { x: new Date(2016, 8), y: 15000 },
            { x: new Date(2016, 9), y:  10000},
            { x: new Date(2016, 10), y: 19000 },
            { x: new Date(2016, 11), y: 22000 }
        ]
    }]
});
chart.render();

function addSymbols(e) {
    var suffixes = ["", "K", "M", "B"];
    var order = Math.max(Math.floor(Math.log(e.value) / Math.log(1000)), 0);

    if(order > suffixes.length - 1)                 
        order = suffixes.length - 1;

    var suffix = suffixes[order];      
    return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
}

function toggleDataSeries(e) {
    if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
        e.dataSeries.visible = false;
    } else {
        e.dataSeries.visible = true;
    }
    e.chart.render();
}
//############## ONLINE TRANSACTIONS ##############//


//############## CASH-OVER-COUNTER ##############//
var chart = new CanvasJS.Chart("chartContainer1", {
    title: {
        text: "Cash over The Counter"
    },
    axisY: {
        title: "Cash (Kwacha)",
        suffix: "ZMW"
    },
    data: [{
        type: "column", 
        yValueFormatString: "#,### ZMW",
        indexLabel: "{y}",
        dataPoints: [
            { label: "Agent1", y: 206 },
            { label: "Agent2", y: 163 },
            { label: "Agent3", y: 154 },
            { label: "Agent4", y: 176 },
            { label: "Agent5", y: 184 },
            { label: "Agent6", y: 122 }
        ]
    }]
});

function updateChart() {
    var boilerColor, deltaY, yVal;
    var dps = chart.options.data[0].dataPoints;
    for (var i = 0; i < dps.length; i++) {
        deltaY = Math.round(2 + Math.random() *(-2-2));
        yVal = deltaY + dps[i].y > 0 ? dps[i].y + deltaY : 0;
        boilerColor = yVal > 200 ? "#0099cc" : yVal >= 170 ? "#8585ad" : yVal < 170 ? "#ff9999" : null;
        dps[i] = {label: "Location "+(i+1) , y: yVal, color: boilerColor};
    }
    chart.options.data[0].dataPoints = dps; 
    chart.render();
};
updateChart();

setInterval(function() {updateChart()}, 500);
}
//############## CASH-OVER-COUNTER ##############//