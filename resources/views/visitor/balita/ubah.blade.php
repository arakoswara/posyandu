@extends('master-home')

@section('content')

<div class="pageheader">

    <h1 class="pagetitle">Data Balita</h1>

    <span class="pagedesc">Data balita posyandu MELATI</span>
    
    <ul class="hornav">
        <li class="current"><a href="#inbox">Ubah Data Balita</a></li>
    </ul>

</div><!--pageheader-->

<div id="contentwrapper" class="contentwrapper">
     
    <div id="inbox" class="subcontent">
     
        <div class="msghead">

        	{!! Form::model($data_balita, ['route' => ['do_ubah_balita', $data_balita->id]]) !!}

                <div class="col-md-6">
                    
                    <div class="form-group">
                        {!! Form::label('no_reg', 'No. Registrasi') !!}

                        {!! Form::text('no_reg', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('nama_balita', 'Nama Balita') !!}

                        {!! Form::text('nama_balita', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('tgl_lahir', 'Tanggal Lahir') !!}

                        {!! Form::text('tgl_lahir', null, ['class' => 'form-control']) !!}
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        {!! Form::label('jenis_kelamin', 'Jenis Kelamin') !!} <br>

                        {!! Form::radio('jenis_kelamin', 'L', ['class' => 'form-control']) !!} Laki-laki
                        &nbsp; &nbsp;&nbsp; 
                        {!! Form::radio('jenis_kelamin', 'P', ['class' => 'form-control']) !!} Perempuan
                    </div>
                     <br>

                    <div class="form-group">
                        {!! Form::label('nama_ayah', 'Nama Ayah') !!}

                        {!! Form::text('nama_ayah', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('nama_ibu', 'Nama Ibu') !!}

                        {!! Form::text('nama_ibu', null, ['class' => 'form-control']) !!}
                    </div>

                </div>

                <div class="col-md-12">
                    
                    <button type="submit" class="btn btn-primary">SIMPAN</button>
                </div>

            {!! Form::close() !!}

            {{-- <ul class="msghead_menu">
            	<li class="right"><a class="next"></a></li>
                <li class="right"><a class="prev prev_disabled"></a></li>
                <li class="right"><span class="pageinfo">1-10 of 2,139</span></li>
            </ul> --}}
            <span class="clearall"></span>
        </div><!--msghead-->
        
                    
    </div>
</div><!--contentwrapper-->

@endsection