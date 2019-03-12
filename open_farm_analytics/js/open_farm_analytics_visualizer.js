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
                        title: $( "#edit-pe option:selected" ).text(),
                        viz_id: 0
                    },
                    success: function (response) {
                        generateChart(response);
                    },
                    error: function (errorResponse) {
                        console.log(errorResponse);
                    }
                });

            });
            $('#edit-stored-charts').once().change(function () {
                var selectedViz = $('#edit-stored-charts').find(":selected").val();

                $.ajax({
                    url: "/open_farm/api/datavalue",
                    contentType: "application/json",
                    type: "get",
                    data: {
                        uuid: selectedViz
                    },
                    success: function (response) {
                        $('#chart').empty();
                        generateChart(response);
                    },
                    error: function (errorResponse) {
                        console.log(errorResponse);
                    }
                });
            });
            $('#edit-save-visual').once().click(function () {
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
                        tags: tags,
                        title: $( "#edit-pe option:selected" ).text()
                    }),
                    success: function (response) {
                        updateSaveVisualizationLists(response);
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
            function updateSaveVisualizationLists(vizId) {
                $.ajax({
                    url: "/open_farm/api/datavalue",
                    contentType: "application/json",
                    type: "get",
                    data: {
                        viz_id: vizId
                    },
                    success: function (response) {
                        $('#edit-stored-charts').append($('<option>', {
                            value: response.uuid,
                            text: response.chart_title
                        }));

                    },
                    error: function (errorResponse) {
                        console.log(errorResponse);
                    }
                });
            }
        }
    };
}(jQuery, Drupal, drupalSettings));
