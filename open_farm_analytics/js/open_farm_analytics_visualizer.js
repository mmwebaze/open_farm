/**
 * @file
 * JavaScript integration between Billboard and Drupal.
 */
(function ($, Drupal, drupalSettings) {
    'use strict';

    Drupal.behaviors.open_farm_analytics_visualizer = {
        attach: function (context, settings) {
            $("#edit-visualize").once().click(function () {
                //var d = drupalSettings.open_farm_analytics.chart_data.d;
                //console.log(d);
                var data = $('#chart').attr('data-chart');


                var chart = bb.generate({
                    bindto: "#chart",
                    data: JSON.parse(data)
                });
            });
        }
    };
}(jQuery, Drupal, drupalSettings));
