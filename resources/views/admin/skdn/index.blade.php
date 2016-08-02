@extends('master-home')

@section('content')

		<div class="pageheader">
		    <div>
		    <h1 class="pagetitle">{{ $user->name }}</h1>
		    <span class="pagedesc">Menu Super Admin</span>
		    </div>
		    <ul class="hornav">
		        <li class="current"><a href="#tambah">Tambah Data SKDN</a></li>
		        <li><a href="#daftar">Daftar Petugas</a></li>
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

		<div id="contentwrapper" class="contentwrapper">
	        <div id="tambah" class="subcontent">

	        	<table class="stdtable" id="dyntable">

	        	    <thead>
	        	        <tr>
	        	            <th>No</th>
	        	            <th>Tanggal</th>
	        	            <th>S</th>
	        	            <th>K</th>
	        	            <th>D</th>
	        	            <th>N</th>
	        	        </tr>
	        	    </thead>
	        	    <tbody>

	        	    	<?php $no = 1; ?>

	        	    	@foreach($skdn as $item)

	        	    	<tr>
	        	    		<td>{{ $no }}</td>
	        	    		<td>{{ $item->date}}</td>
	        	    		<td>{{ $item->s}}</td>
	        	    		<td>{{ $item->k}}</td>
	        	    		<td>{{ $item->d}}</td>
	        	    		<td>{{ $item->n}}</td>
	        	    	</tr>

	        	    	<?php $no++ ?>

	        	    	@endforeach

	        	    </tbody>
	        	</table>
	            
	        </div><!--#profile-->

	        <div class="col-md-12">

	            <hr class="garis">

	            <center>
	                <h4>GRAFIK PENCAPAIAN PROGRAM SKDN</h4>
	            </center>

	            @if (empty($skdn))
	                
	                <h4> Tidak ada riwayat pemeriksaan</h4>

	            @else

	                <div id="grafikBalita" style="width:100%; height: 400px;"></div>

	            @endif

	        </div>

	        <div id="daftar" class="subcontent" style="display: none">

	        		        	{!! Form::open(['route' => 'do_tambah_skdn']) !!}

	        		        	<div class="col-md-6 col-sm-6">
	        		        		
	        		        		<div class="form-group">

	        		        			{!! Form::label('s', 'S') !!}

	        		                    @if($errors->any())
	        		                        @foreach($errors->get('s') as $error)

	        		                        <span style="color:red">
	        		                         * {{ $error }}
	        		                        </span>

	        		                        @endforeach
	        		                    @endif

	        		        			{!! Form::text('s', null , ['class' => 'form-control']) !!}
	        		        		</div>
	        		        	</div>

	        		        	<div class="col-md-6 col-sm-6">
	        		        		
	        		        		<div class="form-group">

	        		        			{!! Form::label('k', 'K') !!}

	        		                    @if($errors->any())
	        		                        @foreach($errors->get('k') as $error)

	        		                        <span style="color:red">
	        		                         * {{ $error }}
	        		                        </span>

	        		                        @endforeach
	        		                    @endif

	        		        			{!! Form::text('k', null , ['class' => 'form-control']) !!}
	        		        		</div>
	        		        	</div>

	        	        		<div class="col-md-6 col-sm-6">
	        	        			
	        	        			<div class="form-group">

	        	        				{!! Form::label('d', 'D') !!}

	        	        	            @if($errors->any())
	        	        	                @foreach($errors->get('d') as $error)

	        	        	                <span style="color:red">
	        	        	                 * {{ $error }}
	        	        	                </span>

	        	        	                @endforeach
	        	        	            @endif

	        	        				{!! Form::text('d', null, ['class' => 'form-control']) !!}
	        	        			</div>
	        	        		</div>

	        	        		<div class="col-md-6 col-sm-6">
	        	        			
	        	        			<div class="form-group">

	        	        				{!! Form::label('n', 'N') !!}

	        	        	            @if($errors->any())
	        	        	                @foreach($errors->get('n') as $error)

	        	        	                <span style="color:red">
	        	        	                 * {{ $error }}
	        	        	                </span>

	        	        	                @endforeach
	        	        	            @endif

	        	        				{!! Form::text('n', null, ['class' => 'form-control']) !!}
	        	        			</div>
	        	        		</div>

	        	        		<div class="col-md-6 col-sm-6">
	        	        			
	        	        			<div class="form-group">

	        	        				{!! Form::label('date', 'Date') !!}

	        	        	            @if($errors->any())
	        	        	                @foreach($errors->get('date') as $error)

	        	        	                <span style="color:red">
	        	        	                 * {{ $error }}
	        	        	                </span>

	        	        	                @endforeach
	        	        	            @endif

	        	        				{!! Form::date('date', null, ['class' => 'form-control']) !!}
	        	        			</div>
	        	        		</div>

	        		        	<div class="col-md-12 col-sm-12">

	        		        		{!! Form::submit('SIMPAN', ['class' => 'btn btn-primary']) !!}

	        		        	</div>

	        		        	{!! Form::close() !!}

	        </div>


	    </div>

<script>

    var chartData = <?php  echo $skdn; ?>

</script>

@endsection