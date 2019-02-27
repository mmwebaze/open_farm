/**
 * @file
 * JavaScript integration between Billboard and Drupal.
 */
(function ($) {
    'use strict';

    Drupal.behaviors.open_farm_analytics_visualizer = {
        attach: function (context, settings) {

            var chart = bb.generate({
                bindto: "#chart",
                data: {
                    type: "bar",
                    columns: [
                        ["data1", 30, 200, 100, 170, 150, 250],
                        ["data2", 130, 100, 140, 35, 110, 50]
                    ]
                }
            });
        }
    };
}(jQuery));
