var chart;

AmCharts.ready(function () {
    // SERIAL CHART
    chart = new AmCharts.AmSerialChart();
    chart.dataProvider = chartData;
    chart.categoryField = "month";
    chart.categoryField = "date";
    chart.startDuration = 1;

    // AXES
    // category
    var categoryAxis = chart.categoryAxis;
    categoryAxis.labelRotation = 90;
    categoryAxis.gridPosition = "start";

    // value
    // in case you don't want to change default settings of value axis,
    // you don't need to create it, as one value axis is created automatically.

    // GRAPH
    var graph = new AmCharts.AmGraph();
    graph.valueField = "zbbu";
    graph.balloonText = " zbbu : <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);
    
    var graph = new AmCharts.AmGraph();
    graph.valueField = "ztbu";
    graph.balloonText = "ztbu: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);
    
    var graph = new AmCharts.AmGraph();
    graph.valueField = "zbbtb";
    graph.balloonText = "zbbtb: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);

    var graph = new AmCharts.AmGraph();
    graph.valueField = "berat_badan";
    graph.balloonText = "berat_badan: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);

    var graph = new AmCharts.AmGraph();
    graph.valueField = "tinggi_badan";
    graph.balloonText = "tinggi_badan: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);


    // SKDN
    var graph = new AmCharts.AmGraph();
    graph.valueField = "s";
    graph.balloonText = " s : <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);
    
    var graph = new AmCharts.AmGraph();
    graph.valueField = "k";
    graph.balloonText = "k: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);
    
    var graph = new AmCharts.AmGraph();
    graph.valueField = "d";
    graph.balloonText = "d: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);

    var graph = new AmCharts.AmGraph();
    graph.valueField = "n";
    graph.balloonText = "n: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);

    // CURSOR
    var chartCursor = new AmCharts.ChartCursor();
    chartCursor.cursorAlpha = 0;
    chartCursor.zoomable = false;
    chartCursor.categoryBalloonEnabled = false;
    chart.addChartCursor(chartCursor);

    chart.creditsPosition = "top-right";

    chart.write("grafikBalita");
});