/**
 * @file
 * JavaScript integration between Billboard and Drupal.
 */
(function ($, Drupal, drupalSettings) {
    'use strict';

    Drupal.behaviors.open_farm_analytics_visualizer = {
        attach: function (context, settings) {
            $("#edit-visualize").once().click(function () {

                var tags = null; //Animal tags
                //get multi select values. For single tag use $('#edit-src').find(":selected").val()
                $('#edit-src').each(function () {
                    tags = $(this).val();
                });

                $.ajax({
                    url: "/open_farm/api/datavalue",
                    contentType: "application/json",
                    type: "get",
                    data: {
                        de: $('#edit-de').find(":selected").val(),
                        pe: $('#edit-pe').find(":selected").val(),
                        tags: tags,
                        title: $( "#edit-pe option:selected" ).text()
                    },
                    success: function (response) {
                        console.log(response);
                        generateChart(response);
                    },
                    error: function (errorResponse) {
                        console.log(errorResponse);
                    }
                });

            });
            $('#edit-save-visual').once().click(function () {
                console.log('Saving this now');
                var tags = null; //Animal tags
                //get multi select values. For single tag use $('#edit-src').find(":selected").val()
                $('#edit-src').each(function () {
                    tags = $(this).val();
                });
                console.log(tags);
                /*var url = "/open_farm/api/visualization/save";
                var de = $('#edit-de').find(":selected").val();
                var pe = $('#edit-pe').find(":selected").val();
                var title = $( "#edit-pe option:selected" ).text();*/

                $.ajax({
                    url: "/open_farm/api/visualization/save",
                    //dataType: 'json',
                    type: "post",
                    contentType: "application/json",
                    data: JSON.stringify({
                        de: $('#edit-de').find(":selected").val(),
                        pe: $('#edit-pe').find(":selected").val(),
                       // tags: tags,
                        title: $( "#edit-pe option:selected" ).text()
                    }),
                    success: function (response) {
                        console.log(response);
                    },
                    error: function (errorResponse) {
                        console.log(errorResponse);
                    }
                });
            });

            function generateChart(data) {
                Highcharts.chart('chart', {
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
            function f() {

            }
        }
    };
}(jQuery, Drupal, drupalSettings));
