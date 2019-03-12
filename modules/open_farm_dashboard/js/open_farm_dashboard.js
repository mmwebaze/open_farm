(function ($, Drupal, drupalSettings) {
    'use strict';

    Drupal.behaviors.open_farm_dashboard = {
        attach: function (context, settings) {
            var positions = drupalSettings.dashlets;

console.log(positions);
            for(var uuid in positions){

                //console.log(JSON.parse(positions[uuid]).series);
                generateChart(uuid, JSON.parse(positions[uuid]));
               // console.log(positions[i]);
            }

            function generateChart(position, data){
                Highcharts.chart(position, {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: data.title
                    },
                    xAxis: {
                        categories: data.categories,
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
                    series: data.series
                });
            }

        }
    }

}(jQuery, Drupal, drupalSettings));