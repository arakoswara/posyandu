@extends('master-home')

@section('content')
    
    <div class="pageheader">
        <div>
        <h1 class="pagetitle">{{ $user->name }}</h1>
        <span class="pagedesc">Front-End Engineer / UI Designer</span>
        </div>
        <ul class="hornav">
            <li class="current"><a href="#profile">Tambah Petugas</a></li>
            <li><a href="#editprofile">Daftar Petugas</a></li>
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

            	{!! Form::open(['route' => 'do-Register']) !!}

            	<div class="col-md-6 col-sm-6">
            		
            		<div class="form-group">

            			{!! Form::label('email', 'Email Petugas') !!}

                        @if($errors->any())
                            @foreach($errors->get('email') as $error)

                            <span style="color:red">
                             * {{ $error }}
                            </span>

                            @endforeach
                        @endif

            			{!! Form::text('email', null , ['class' => 'form-control']) !!}
            		</div>
            	</div>

            	<div class="col-md-6 col-sm-6">
            		
            		<div class="form-group">

            			{!! Form::label('name', 'Nama Petugas') !!}

                        @if($errors->any())
                            @foreach($errors->get('name') as $error)

                            <span style="color:red">
                             * {{ $error }}
                            </span>

                            @endforeach
                        @endif

            			{!! Form::text('name', null , ['class' => 'form-control']) !!}
            		</div>
            	</div>

            		<div class="col-md-6 col-sm-6">
            			
            			<div class="form-group">

            				{!! Form::label('password', 'Password Petugas') !!}

            	            @if($errors->any())
            	                @foreach($errors->get('password') as $error)

            	                <span style="color:red">
            	                 * {{ $error }}
            	                </span>

            	                @endforeach
            	            @endif

            				{!! Form::text('password', null, ['class' => 'form-control']) !!}
            			</div>
            		</div>

            	<div class="col-md-12 col-sm-12">

            		{!! Form::submit('SIMPAN', ['class' => 'btn btn-primary']) !!}

            	</div>

            	{!! Form::close() !!}
                
            </div><!--#profile-->
        
        <br /><br />
        
    </div><!--contentwrapper-->



@endsection