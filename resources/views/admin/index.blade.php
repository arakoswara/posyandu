@extends('master-home')

@section('content')

@if(Session::has('message'))

	<div data-alert class="alert-box warning round">
	  	{{ session('message') }}
	  	<a href="#" class="close">&times;</a>
	</div>
	
@endif

<h1>HALAMAN ADMIN</h1>

<h3>hai... {{ $user->name }}</h3>

<a href="{{ route('do-Logout') }}" title="">Logout</a>

@endsection