/**
 * Created by Evgeniy Belov on 05.01.2016.
 */

function refreshKendoChart(){
    var chart = $("#chart").data("kendoChart");
    chart.refresh();
}

function createChart() {
    $("#chart").kendoChart({
        dataSource: {
            transport: {
                read: {
                    url: location.href,
                    data: {ajaxurl: 1},
                    dataType: "json"
                }
            },
            sort: {
                field: "year",
                dir: "asc"
            }
        },
        autoBind: true,
        transitions: true,
            title: {
            text: "Spain electricity production (GWh)"
        },
        legend: {
            position: "top"
        },
        seriesDefaults: {
            type: "line"
        },
        series: [{
            field: "Humidity",
            name: "Humidity"
        }],
        categoryAxis: {
            field: "ts",
            labels: {
                rotation: -90
            },
            crosshair: {
                visible: true
            }
        },
        valueAxis: {
            labels: {
                format: "N0"
            },
            majorUnit: 100
        },
        tooltip: {
            visible: true,
            shared: true,
            format: "N0"
        }
    });

    setInterval(refreshKendoChart, 20000);
}

$(document).ready(createChart);
$(document).bind("kendo:skinChange", createChart);