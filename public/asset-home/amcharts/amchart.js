var chart;

AmCharts.ready(function () {
    // SERIAL CHART
    chart = new AmCharts.AmSerialChart();
    chart.dataProvider = chartData;
    chart.categoryField = "month";
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
    graph.valueField = "protein";
    graph.balloonText = "protein: <b>[[value]]</b>";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);

    var graph = new AmCharts.AmGraph();
    graph.valueField = "energi";
    graph.balloonText = "energi: <b>[[value]]</b>";
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