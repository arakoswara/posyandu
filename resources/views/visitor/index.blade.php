@extends('master-home')
@section('content')
<div class="pageheader">
    <h1 class="pagetitle">Data Balita</h1>
    <span class="pagedesc">Data balita posyandu MELATI</span>
    <ul class="hornav">
        <li class="current"><a href="#inbox">Dashboard</a></li>
    </ul>
</div>
<!--pageheader-->
<div id="contentwrapper" class="contentwrapper">
    <div id="inbox" class="subcontent">
        <div class="msghead">
            <div class="col-md-12">

                <h1>Selamat Datang </h1>

                <h1>Jumlah Balita : {{ $jml_balita }} </h1>

            </div>
        </div>
    </div>
</div>
@endsection