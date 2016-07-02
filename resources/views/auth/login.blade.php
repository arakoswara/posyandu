@extends('master-admin')

@section('content')

    <!-- Section: intro -->
    <section id="intro" class="intro">
        <div class="intro-content">
            <div class="container" style="min-height:420px">
                <div class="row">

                    <div class="col-lg-6">

                         
                        <div class="wow fadeInDown" data-wow-offset="0" data-wow-delay="0.1s">
                            <h2 class="h-ultra">Posyandu Melati</h2>
                        </div>
                        <div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.1s">
                            <h4 class="h-light">Melayani Dengan Sepenuh<span style="color:red"> <i class="fa fa-heart"></i> </span></h4>
                        </div>

                        <div class="well well-trans">
                            <div class="wow fadeInRight" data-wow-delay="0.1s">

                            <ul class="lead-list">
                                <li><span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Halaman login</strong><br />Hanya dikhusukan untuk petugas posyandu</span></li>
                                <li><span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Akun Petugas</strong><br />Pendaftaran akun petugas hanya bisa dilakukan oleh <br> kepala posyandu</span></li>
                                
                            </ul>

                            </div>
                        </div>       
                    </div>

                    <div class="col-lg-6">
                        <div class="form-wrapper">
                            <div class="wow fadeInRight" data-wow-duration="2s" data-wow-delay="0.2s">

                                <div class="panel panel-skin">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><span class="fa fa-lock"></span> Login Petugas Posyandu</small></h3>
                                    </div>

                                    <div class="panel-body">

                                        {!! Form::open(['route' => 'do-Login']) !!}

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="form-group">
                                                        <label>Email</label>

                                                        <small style="color:red;">*
                                                            @if($errors->any())
                                                                
                                                                @foreach($errors->get('email') as $error)

                                                                  {{ $error }}

                                                                @endforeach

                                                            @endif
                                                        </small>

                                                        <input type="text" name="email" id="email" class="form-control input-lg">
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12">
                                                    <div class="form-group">
                                                        <label>Password</label> 

                                                        <small style="color:red;">*
                                                            @if($errors->any())
                                                                
                                                                @foreach($errors->get('password') as $error)

                                                                  {{ $error }}

                                                                @endforeach

                                                            @endif
                                                        </small>

                                                        <input type="password" name="password" id="password" class="form-control input-lg">
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            <button type="submit" class="btn btn-success btn-block btn-lg">
                                                <i class="fa fa-sign-in"></i> Login
                                            </button>

                                            <small style="color:red;">*
                                                @if(Session::has('message'))

                                                    <div class="alert alert-success">

                                                        {{ session('message') }}
                                                        
                                                        <a href="#" class="close">&times;</a>
                                                    </div>
                                                    
                                                @endif
                                                
                                            </small>
                                        
                                        {!! Form::close() !!}
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