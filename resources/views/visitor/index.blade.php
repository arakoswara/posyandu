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
        
            
            
            <h1>Jumlah Data Balita : {{ $jml_balita }} Balita</h1>
        </div>
    </div>

    <div class="col-md-12">

        <hr class="garis">

        <center>
            <h3>GRAFIK PERSENTASE STATUS GIZI </h3>
        </center>

        @if (empty($skdn))
            
            <h4> Tidak ada riwayat pemeriksaan</h4>

        @else

            <div id="grafikGizi" style="width:100%; height: 400px;"></div>

            <br><br>
            
            <hr class="garis">

        <center>
            <h3>JUMLAH DATA PROGRAM SKDN </h3>
        </center>

            <div class="col-md-6">
                @foreach($skdn as $item)

                    <ul>
                        <li>S : {{ $item->s }} data</li>
                        <li>K : {{ $item->k }} data</li>
                        <li>D : {{ $item->d }} data</li>
                        <li>N : {{ $item->n }} data</li>
                    </ul>

                @endforeach
            </div>

            <div class="col-md-6">
                    <ul>
                        <li>S = jumlah balita yang ada diwilayah posyandu</li>
                        <li>K = jumlah balita yang memiliki KMS</li>
                        <li>D = jumlah balita yang datang ditimbang bulan ini</li>
                        <li>N = jumlah balita yang naik berat badannya.</li>
                    </ul>
            </div>
            {{-- <div class="col-md-4">
                <h4>Persentase Gizi Bulan Maret</h4>
                <ol>
                    <li>Gizi Buruk = {{ $persentase_maret['buruk'] }} %</li>
                    <li>Gizi Kurang = {{ $persentase_maret['kurang'] }} %</li>
                    <li>Gizi Baik = {{ $persentase_maret['baik'] }} %</li>
                    <li>Gizi Lebih = {{ $persentase_maret['lebih'] }} %</li>
                </ol>
            </div>

            <div class="col-md-4">
                <h4>Persentase Gizi Bulan April</h4>
                <ol>
                    <li>Gizi Buruk = {{ $persentase_april['buruk'] }} %</li>
                    <li>Gizi Kurang = {{ $persentase_april['kurang'] }} %</li>
                    <li>Gizi Baik = {{ $persentase_april['baik'] }} %</li>
                    <li>Gizi Lebih = {{ $persentase_april['lebih'] }} %</li>
                </ol>
            </div>

            <div class="col-md-4">
                <h4>Persentase Gizi Bulan Mei</h4>
                <ol>
                    <li>Gizi Buruk = {{ $persentase_mei['buruk'] }} %</li>
                    <li>Gizi Kurang = {{ $persentase_mei['kurang'] }} %</li>
                    <li>Gizi Baik = {{ $persentase_mei['baik'] }} %</li>
                    <li>Gizi Lebih = {{ $persentase_mei['lebih'] }} %</li>
                </ol>
            </div> --}}

        @endif

    </div>

</div>

<script>

    var chartData2 = <?php  echo $gizi; ?>

</script>

<script>
    var chart2;

    AmCharts.ready(function () {
        // SERIAL CHART
        chart2 = new AmCharts.AmSerialChart();
        chart2.dataProvider = chartData2;
        chart2.categoryField = "month";
        chart2.startDuration = 1;

        // AXES
        // category
        var categoryAxis = chart2.categoryAxis;
        categoryAxis.labelRotation = 90;
        categoryAxis.gridPosition = "start";

        // value
        // in case you don't want to change default settings of value axis,
        // you don't need to create it, as one value axis is created automatically.

        // SKDN
        var graph2 = new AmCharts.AmGraph();
        graph2.valueField = "gizi_buruk";
        graph2.balloonText = " Gizi Buruk : <b>[[value]]</b>";
        graph2.type = "column";
        graph2.lineAlpha = 0;
        graph2.fillAlphas = 0.8;
        chart2.addGraph(graph2);

        var graph2 = new AmCharts.AmGraph();
        graph2.valueField = "gizi_kurang";
        graph2.balloonText = " Gizi Kurang : <b>[[value]]</b>";
        graph2.type = "column";
        graph2.lineAlpha = 0;
        graph2.fillAlphas = 0.8;
        chart2.addGraph(graph2);

        var graph2 = new AmCharts.AmGraph();
        graph2.valueField = "gizi_baik";
        graph2.balloonText = " Gizi Baik : <b>[[value]]</b>";
        graph2.type = "column";
        graph2.lineAlpha = 0;
        graph2.fillAlphas = 0.8;
        chart2.addGraph(graph2);

        var graph2 = new AmCharts.AmGraph();
        graph2.valueField = "gizi_lebih";
        graph2.balloonText = " Gizi Lebih : <b>[[value]]</b>";
        graph2.type = "column";
        graph2.lineAlpha = 0;
        graph2.fillAlphas = 0.8;
        chart2.addGraph(graph2);
        

        // CURSOR
        var chartCursor2 = new AmCharts.ChartCursor();
        chartCursor2.cursorAlpha = 0;
        chartCursor2.zoomable = false;
        chartCursor2.categoryBalloonEnabled = false;
        chart2.addChartCursor(chartCursor2);

        chart2.creditsPosition = "top-right";

        chart2.write("grafikGizi");
    });
</script>

@endsection