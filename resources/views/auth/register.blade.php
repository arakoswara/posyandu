@extends('master-home')

@section('content')

	{!! Form::open(['route' => 'do-Register']) !!}

 	<div class="row">
    	<div class="large-4 columns">

    		@if($errors->any())
    		  
    		  <ul class="alert alert-danger">
    		    
    		    @foreach($errors->all() as $error)

    		      <li style="margin-left:5px;">{{ $error }}</li>

    		    @endforeach

    		  </ul>

    		@endif

	      	<label> Name
	        	{!! Form::text('name', null) !!}
	      	</label>

	      	<label> Email
	        	{!! Form::email('email', null) !!}
	      	</label>

	      	<label> Password
	        	{!! Form::password('password', null) !!}
	      	</label>

	      	<label>
	        	{!! Form::submit('Submit', ['class' => 'button radius']) !!}
	      	</label>

    	</div>
  	</div>

	{!! Form::close() !!}

	<div class="row">
		<div class="large-4 columns">
			<a href="{{ route('login-form') }}" title="">LOGIN</a>
		</div>
	</div>

@endsection