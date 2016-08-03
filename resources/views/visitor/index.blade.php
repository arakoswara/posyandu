@extends('master-home')
@section('content')
<div class="pageheader">
    <h1 class="pagetitle">Dashboard</h1>
    <span class="pagedesc">Data balita posyandu MELATI</span>
    <ul class="hornav">
        <li class="current"><a href="#inbox">Dashboard</a></li>
    </ul>
</div>
<!--pageheader-->
<div id="contentwrapper" class="contentwrapper">

    <div id="updates" class="subcontent">
        <div class="notibar announcement">
            <a class="close"></a>
            <h3>Announcement</h3>
            <p>
                Selamat datang
            </p>
        </div><!-- notification announcement -->
        
        <div class="two_third dashboard_left">
        
            
            
            <h1>Jumlah Balita : {{ $jml_balita }} </h1>
        </div>
    </div>

    <div class="col-md-12">

        <hr class="garis">

        <center>
            <h4>GRAFIK PENCAPAIAN PROGRAM SKDN</h4>
        </center>

        @if (empty($skdn))
            
            <h4> Tidak ada riwayat pemeriksaan</h4>

        @else

            <div id="grafikSKDN" style="width:100%; height: 400px;"></div>

        @endif

    </div>

</div>

<script>

    var chartData = <?php  echo $skdn; ?>

</script>

<script>
    var chart;

    AmCharts.ready(function () {
        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();
        chart.dataProvider = chartData;
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

        chart.write("grafikSKDN");
    });
</script>

@endsection