(function ($, Drupal, drupalSettings) {
    'use strict';

    Drupal.behaviors.open_farm_dashboard = {
        attach: function (context, settings) {
            //var dashboard = '<div class="dashboard-container"></div>';
            $(context).find('.dashboard-container').once('open_farm_dashboard').each(function () {
                var charts = drupalSettings.charts;
                var rows = drupalSettings.rows;
                var cols = drupalSettings.cols;
                var ids = drupalSettings.ids;

                console.log(rows+' -- '+cols);
                var tempDiv = '';
                var count = 0;
               /* for (var row = 0; row < rows; row++){
                    var rw = '<div>';
                    var colm = '<div class="columns">';
                    for(var col = 0; col < cols; col++){
                        var divId = ids[count];

                        if (divId !== undefined){
                            colm += '<div id='+ids[count]+' class="column is-one-third">*'+ids[count]+'*</div>';
                            count++;
                        }
                    }
                    rw = colm+'</div></div>';
                    //$('.dashboard-container').prepend(rw);
                    tempDiv += rw;
                }
                $('.dashboard-container').append(tempDiv);*/
                for(var uuid in charts){

                    console.log(uuid);
                    console.log(JSON.parse(charts[uuid]).series);
                    generateChart(uuid, JSON.parse(charts[uuid]));
                    console.log(uuid);
                }
            });



//console.log(positions);


            function generateChart(position, data){
                var chart = Highcharts.chart(position, {
                    chart: {
                        type: 'column'
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

        }
    }

}(jQuery, Drupal, drupalSettings));