@extends('master-admin')
@section('content')
<!-- Section: intro -->
<section id="intro" class="intro">
    <div class="intro-content">
        <div class="container">
            <div class="row">

                <div class="col-lg-6">

                    <div class="form-wrapper">
                        <div class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.2s">
                            <div class="panel panel-skin">

                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-search"></i> Pencarian Data Balita</small>
                                    </h3>
                                </div>

                                <div class="panel-body">
                                    
                                    {!! Form::open(['route' => 'do_pencarian']) !!}
                                        
                                        <div class="row">

                                            <div class="col-xs-12 col-sm-12 col-md-12">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <input type="text" name="no_reg" id="phone" class="form-control input-lg" placeholder="ID / No. Registrasi Balita anda..">
                                                </div>
                                            </div>

                                        </div>

                                        <button type="submit" class="btn btn-success btn-block btn-lg">
                                            <i class="fa fa-search"></i> Cari
                                        </button>

                                    {!! Form::close() !!}

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wow fadeInDown" data-wow-offset="0" data-wow-delay="0.1s">
                        <h2 class="h-ultra">Posyandu Melati</h2>
                    </div>
                    <div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.1s">
                        <h4 class="h-light">Melayani Dengan Sepenuh<span style="color:red"> <i class="fa fa-heart"></i> </span></h4>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="form-wrapper">
                        <div class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.2s">
                            <div class="panel panel-skin">

                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-stethoscope"></i> Form Pemeriksaan Balita</small>
                                    </h3>
                                </div>

                                <div class="panel-body">
                                    <form role="form" class="lead">
                                        <div class="row">

                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>ID Balita</label> <small style="color:red;">*</small>
                                                    <input type="text" name="first_name" id="first_name" class="form-control input-lg">
                                                </div>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Periksa</label>  <small style="color:red;">*</small>
                                                    <input type="date" name="last_name" id="last_name" class="form-control input-lg">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Berat Badan</label>  <small style="color:red;">*</small>
                                                    <input type="email" name="email" id="email" class="form-control input-lg">
                                                </div>
                                            </div>

                                            <div class="col-xs-6 col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label>Tinggi Badan</label>  <small style="color:red;">*</small>
                                                    <input type="text" name="phone" id="phone" class="form-control input-lg">
                                                </div>
                                            </div>

                                        </div>

                                        <button type="submit" class="btn btn-success btn-block btn-lg">
                                            <i class="fa fa-stethoscope"></i> PEMERIKSAAN
                                        </button>

                                        <p class="lead-footer">* Data hasil pemeriksaan hanya bersifat sementara</p>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection