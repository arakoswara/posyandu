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
    
    <div id="contentwrapper" class="contentwrapper">
            <div id="profile" class="subcontent">

            	{!! Form::open(['route' => 'do_edit_profil']) !!}

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
                {!! Form::open() !!}

                <div class="col-md-6 col-sm-6">
                	
                	<div class="form-group">

                		{!! Form::label('email', 'Password Lama') !!}

                		{!! Form::text('email', null, ['class' => 'form-control']) !!}
                	</div>
                </div>

                <div class="col-md-6 col-sm-6">
                	
                	<div class="form-group">

                		{!! Form::label('name', 'Password Baru') !!}

                		{!! Form::text('name', null, ['class' => 'form-control']) !!}
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