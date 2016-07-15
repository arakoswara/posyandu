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
</div>
@endsection