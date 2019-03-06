/**
 * @file
 * JavaScript integration between Billboard and Drupal.
 */
(function ($, Drupal, drupalSettings) {
    'use strict';

    Drupal.behaviors.open_farm_analytics_visualizer = {
        attach: function (context, settings) {
            $("#edit-visualize").once().click(function () {

                //generateChart();
                //highchart()
                $.ajax({
                    url: "/open_farm/api/datavalue",
                    contentType: "application/json",
                    type: "get",
                    data: {
                        de: $('#edit-de').find(":selected").val(),
                        pe: $('#edit-pe').find(":selected").val(),
                        tags: $('#edit-src').find(":selected").val()
                    },
                    success: function (response) {
                        console.log(response.series);
                        //console.log(JSON.stringify(response));
                        highchart(response.series);
                    },
                    error: function (errorResponse) {
                        console.log(errorResponse);
                    }
                });

            });
            function generateChart() {

            }
            function highchart(data) {
                Highcharts.chart('chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Monthly Average Rainfall'
                    },
                    xAxis: {
                        categories: [
                            'Jan',
                            'Feb'
                        ],
                        crosshair: true
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'litres'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: data
                });
            }
        }
    };
}(jQuery, Drupal, drupalSettings));
