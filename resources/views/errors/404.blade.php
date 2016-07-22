@extends('master-admin')

@section('content')

<section id="intro" class="intro">
    <div class="intro-content">
        <div class="container" style="min-height:420px">
            <div class="row">

                <div class="col-lg-12">

                     
                    <div class="wow fadeInDown" data-wow-offset="0" data-wow-delay="0.1s">
                        <h1 class="h-ultra">404, halaman yang anda tuju tidak ditemukan</h1>
                    </div>

                    <div class="well well-trans">
                        <div class="wow fadeInRight" data-wow-delay="0.1s">

                        <ul class="lead-list">
                            <li><span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>
                            404. Not Found
                            </strong><br />
                            Silahkan cek URL yang anda masukkan, karena sistem kami tidak dapat menemukan halaman yang anda tuju</span></li>
                            
                        </ul>

                        </div>
                    </div>       
                </div>
            </div>
        </div>
    </div>
</section>

@endsection