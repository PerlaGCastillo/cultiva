<!DOCTYPE HTML>
<html>
    <head>
    <title>Dirección General de Carreteras</title>

    <!-- BEGIN META -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="your,keywords">
    <meta name="description" content="Short explanation about this website">
    <!-- END META -->

    <!-- BEGIN STYLESHEETS -->
    {!! Html::style('http://fonts.googleapis.com/css?family=Roboto:300italic,400italic,300,400,500,700,900')!!}
    {!! Html::style('assets/css/theme-default/bootstrap.css?1422792965')!!}
    {!! Html::style('assets/css/theme-default/materialadmin.css?1425466319')!!}
    {!! Html::style('assets/css/theme-default/font-awesome.min.css?1422529194')!!}
    {!! Html::style('assets/css/theme-default/material-design-iconic-font.min.css?1421434286')!!}
    {!! Html::style('assets/css/theme-default/libs/rickshaw/rickshaw.css?1422792967')!!}
    {!! Html::style('assets/css/theme-default/libs/morris/morris.core.css?1420463396')!!}
    {!! Html::style('assets/css/theme-default/libs/DataTables/jquery.dataTables.css?1423553989')!!}
    {!! Html::style('assets/css/theme-default/libs/DataTables/extensions/dataTables.colVis.css?1423553990')!!}
    {!! Html::style('assets/css/theme-default/libs/DataTables/extensions/dataTables.tableTools.css?1423553990')!!}

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <style type="text/css">
* {
    font-family: sans-serif;
}
#wrapper {
    height: 500px;
    width: 1000px;
    margin: 0 auto;
    padding: 0;
}
#container {
    float: left;
    height: 500px; 
    width: 700px; 
    margin: 0;
}
#info {
    float: left;
    width: 270px;
    padding-left: 20px;
    margin: 100px 0 0 0;
    border-left: 1px solid silver;
}
#info h2 {
    display: inline;
    font-size: 13pt;
}
#info .f32 .flag {
    vertical-align: bottom !important;
}

#info h4 {
    margin: 1em 0 0 0;
}
        </style>
        <script type="text/javascript">
$(function () {

    // Load the data from a Google Spreadsheet
    // https://docs.google.com/a/highsoft.com/spreadsheet/pub?hl=en_GB&hl=en_GB&key=0AoIaUO7wH1HwdDFXSlpjN2J4aGg5MkVHWVhsYmtyVWc&output=html
    Highcharts.data({

        googleSpreadsheetKey: '0AoIaUO7wH1HwdDFXSlpjN2J4aGg5MkVHWVhsYmtyVWc',

        // custom handler for columns
        parsed: function (columns) {

            /**
             * Event handler for clicking points. Use jQuery UI to pop up
             * a pie chart showing the details for each state.
             */
            function pointClick() {
                var row = this.options.row,
                    $div = $('<div></div>')
                        .dialog({
                            title: this.name,
                            width: 400,
                            height: 300
                        });

                window.chart = new Highcharts.Chart({
                    chart: {
                        renderTo: $div[0],
                        type: 'pie',
                        width: 370,
                        height: 240
                    },
                    title: {
                        text: null
                    },
                    series: [{
                        name: 'Votes',
                        data: [{
                            name: 'Obama',
                            color: '#0200D0',
                            y: parseInt(columns[3][row], 10)
                        }, {
                            name: 'Romney',
                            color: '#C40401',
                            y: parseInt(columns[4][row], 10)
                        }],
                        dataLabels: {
                            format: '<b>{point.name}</b> {point.percentage:.1f}%'
                        }
                    }]
                });
            }

            // Make the columns easier to read

            var keys = columns[0],
                names = columns[1],
                percent = columns[7],
                // Build the chart options
                options = {
                    chart: {
                        renderTo: 'container',
                        type: 'map',
                        borderWidth: 1
                    },

                    title: {
                        text: 'US presidential election 2012 results'
                    },
                    subtitle: {
                        text: 'Source: <a href="http://en.wikipedia.org/wiki/United_States_presidential_election,' +
                            '_2012">Wikipedia</a>'
                    },

                    legend: {
                        align: 'right',
                        verticalAlign: 'top',
                        x: -100,
                        y: 70,
                        floating: true,
                        layout: 'vertical',
                        valueDecimals: 0,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || 'rgba(255, 255, 255, 0.85)'
                    },

                    mapNavigation: {
                        enabled: true,
                        enableButtons: false
                    },

                    colorAxis: {

                        dataClasses: [{
                            from: -100,
                            to: 0,
                            color: '#C40401',
                            name: 'Romney'
                        }, {
                            from: 0,
                            to: 100,
                            color: '#0200D0',
                            name: 'Obama'
                        }]
                    },

                    series: [{
                        data: [],
                        mapData: Highcharts.maps['countries/mx/mx-all'],
                        joinBy: 'postal-code',
                        dataLabels: {
                            enabled: true,
                            color: '#FFFFFF',
                            format: '{point.postal-code}',
                            style: {
                                textTransform: 'uppercase'
                            }
                        },
                        name: 'Democrats margin',
                        point: {
                            events: {
                                click: pointClick
                            }
                        },
                        tooltip: {
                            ySuffix: ' %'
                        },
                        cursor: 'pointer'
                    }, {
                        name: 'Separators',
                        type: 'mapline',
                        data: Highcharts.geojson(Highcharts.maps['countries/mx/mx-all'], 'mapline'),
                        color: 'silver',
                        showInLegend: false,
                        enableMouseTracking: false
                    }]
                };
            keys = keys.map(function (key) {
                return key.toUpperCase();
            });
            Highcharts.each(options.series[0].mapData.features, function (mapPoint) {
                if (mapPoint.properties['postal-code']) {
                    var postalCode = mapPoint.properties['postal-code'],
                        i = $.inArray(postalCode, keys);
                    options.series[0].data.push(Highcharts.extend({
                        value: parseFloat(percent[i]),
                        name: names[i],
                        'postal-code': postalCode,
                        row: i
                    }, mapPoint));
                }
            });

            // Initiate the chart

            window.chart = new Highcharts.Map(options);
        },

        error: function () {
            $('#container').html('<div class="loading">' +
                '<i class="icon-frown icon-large"></i> ' +
                '<p>Error loading data from Google Spreadsheets</p>' +
                '</div>');
        }
    });
});
        </script>
    </head>
    <body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/maps/modules/map.js"></script>
<script src="https://code.highcharts.com/maps/modules/data.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/mx/mx-all.js"></script>
<script src="https://code.highcharts.com/mapdata/countries/us/us-all.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
   
<link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<link href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet" type="text/css" />

<div id="container">
    <div class="loading">
        <i class="icon-spinner icon-spin icon-large"></i>
        Loading data from Google Spreadsheets...
    </div>
</div>

    </body>
</html>
