@extends('master-home')

@section('content')
    
    <div class="pageheader">
        <div>
        <h1 class="pagetitle">{{ $user->name }}</h1>
        <span class="pagedesc">Front-End Engineer / UI Designer</span>
        </div>
        <ul class="hornav">
            <li class="current"><a href="#profile">Edit Profil</a></li>
            <li><a href="#editprofile">Ganti Password</a></li>
        </ul>
    </div><!--pageheader-->

    @if(Session::has('message'))

    <div id="contentwrapper" class="contentwrapper">
        <div class="notibar announcement">
            <a class="close"></a>
            <h3>Perhatian</h3>           
            <p>
                {{ session('message') }}
            </p>
        </div><!-- notification announcement -->
    </div>

    @endif

    @if($errors->any())

    <div id="contentwrapper" class="contentwrapper">
        <div class="notibar announcement">
            <a class="close"></a>
            <h3>Perhatian</h3>

            @foreach($errors->all() as $error)          
            <p>
                {{ $error }}
            </p>
            @endforeach
        </div><!-- notification announcement -->
    </div>

    @endif
    
    <div id="contentwrapper" class="contentwrapper">
            <div id="profile" class="subcontent">

                <div class="col-md-12">
                    <h4>
                        Email Petugas : {{ $user->email }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nama Petugas : {{ $user->name }}</h4>
                </div>

                <hr class="garis">

            	{!! Form::open(['route' => 'do_edit_profil']) !!}

                {!! Form::hidden('id', $user->id , ['class' => 'form-control']) !!}

            	<div class="col-md-6 col-sm-6">
            		
            		<div class="form-group">

            			{!! Form::label('email', 'Email anda') !!}

                        @if($errors->any())
                            @foreach($errors->get('email') as $error)

                            <span style="color:red">
                             * {{ $error }}
                            </span>

                            @endforeach
                        @endif

            			{!! Form::text('email', $user->email , ['class' => 'form-control']) !!}
            		</div>
            	</div>

            	<div class="col-md-6 col-sm-6">
            		
            		<div class="form-group">

            			{!! Form::label('name', 'Nama anda') !!}

                        @if($errors->any())
                            @foreach($errors->get('name') as $error)

                            <span style="color:red">
                             * {{ $error }}
                            </span>

                            @endforeach
                        @endif

            			{!! Form::text('name', $user->name , ['class' => 'form-control']) !!}
            		</div>
            	</div>

            	<div class="col-md-12 col-sm-12">

            		{!! Form::submit('SIMPAN', ['class' => 'btn btn-primary']) !!}

            	</div>

            	{!! Form::close() !!}
                
            </div><!--#profile-->
            
            <div id="editprofile" class="subcontent" style="display: none">

                {!! Form::open(['route' => 'ganti_password_petugas']) !!}

                <div class="col-md-4 col-sm-4">
                	
                	<div class="form-group">

                		{!! Form::label('old_password', 'Password Lama') !!}

                		{!! Form::password('old_password', ['class' => 'form-control']) !!}
                	</div>
                </div>

                <div class="col-md-4 col-sm-4">
                	
                	<div class="form-group">

                		{!! Form::label('password', 'Password Baru') !!}

                		{!! Form::password('password', ['class' => 'form-control']) !!}
                	</div>
                </div>

                <div class="col-md-4 col-sm-4">
                    
                    <div class="form-group">

                        {!! Form::label('password_confirmation', 'Konfirmasi Password Baru') !!}

                        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                    </div>
                </div>

                <div class="col-md-12 col-sm-12">

                	{!! Form::submit('SIMPAN', ['class' => 'btn btn-primary']) !!}

                </div>

                {!! Form::close() !!}
            </div><!--#editprofile-->
        
        <br /><br />
        
    </div><!--contentwrapper-->



@endsection