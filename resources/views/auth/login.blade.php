@extends('master-home')

@section('content')

	{!! Form::open(['route' => 'do-Login']) !!}

 	<div class="row">
    	<div class="large-4 columns">

    		@if($errors->any())
    		  
    		  <ul class="alert alert-danger">
    		    
    		    @foreach($errors->all() as $error)

    		      <li style="margin-left:5px;">{{ $error }}</li>

    		    @endforeach

    		  </ul>

    		@endif

	      	<label> Email
	        	{!! Form::email('email', null) !!}
	      	</label>

	      	<label> Password
	        	{!! Form::password('password', null) !!}
	      	</label>

	      	<label>
	        	{!! Form::submit('Submit', ['class' => 'button radius', 'onClick' => 'show()']) !!}
	      	</label>

    	</div>
  	</div>

	{!! Form::close() !!}
	
<style>
.loader {
  border: 10px solid #f3f3f3;
  border-radius: 50%;
  border-top: 10px solid #3498db;
  width: 50px;
  height: 50px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>

<script>
var myVar;

function myFunction() {
    myVar = setTimeout(showPage, 3000);
}

function showPage() {
  document.getElementById("loader").style.display = "none";
  document.getElementById("myDiv").style.display = "block";
}
</script>

</head>
<body>

<div class="loader" onload="myFunction()"></div>

@endsection