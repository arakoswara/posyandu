@if(Session::has('message'))
	
	<div data-alert class="alert-box warning round">
	  	{{ session('message') }}
	  	<a href="#" class="close">&times;</a>
	</div>

@endif

<br>

<a href="{{ url('/auth/active/'. $data['code']) }}">{{ $data['code'] }}</a>