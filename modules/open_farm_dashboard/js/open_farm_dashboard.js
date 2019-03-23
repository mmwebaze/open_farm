(function ($, Drupal, drupalSettings) {
    'use strict';

    Drupal.behaviors.open_farm_dashboard = {
        attach: function (context, settings) {
            $(context).find('.dashboard-container').once('open_farm_dashboard').each(function () {
                var charts = drupalSettings.charts;

                for(var uuid in charts){
                    console.log(uuid);
                    console.log(JSON.parse(charts[uuid]).series);

                    if (JSON.parse(charts[uuid]).chart === 'pie'){
                        generatePieChart(uuid, JSON.parse(charts[uuid]));
                    }
                    else{
                        generateChart(uuid, JSON.parse(charts[uuid]));
                    }
                }
            });

            function generateChart(position, data){
                var chart = Highcharts.chart(position, {
                    chart: {
                        type: data.chart
                        //width: 400
                    },
                    title: {
                        text: data.title
                    },
                    xAxis: {
                        categories: data.categories,
                        crosshair: true,
                        scrollbar: {
                            enabled: true
                        }
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
                    series: data.series
                });
                chart.reflow();
            }
            function generatePieChart(position, data) {
                var pieData = [];
                for(var i = 0; i < data.series.length; i++){
                    var sum = 0;
                    for (var j = 0; j < data.series[i].data.length; j++){
                        sum += data.series[i].data[j];
                    }
                    var d = {name: data.series[i].name, y: sum};
                    pieData.push(d);
                }

                Highcharts.chart(position, {
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: data.title
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: false
                            },
                            showInLegend: true
                        }
                    },
                    series: [{data: pieData}]
                });
            }

        }
    }

}(jQuery, Drupal, drupalSettings));