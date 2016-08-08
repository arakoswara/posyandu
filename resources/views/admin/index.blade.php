@extends('master-home')

@section('content')
    
    <div class="pageheader">
        <div>
        <h1 class="pagetitle">{{ $user->name }}</h1>
        <span class="pagedesc">Menu Data Petugas</span>
        </div>
        <ul class="hornav">
            <li><a href="#daftar">Daftar Petugas</a></li>
            <li class="current"><a href="#tambah">Tambah Petugas</a></li>
            
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
            <div id="tambah" class="subcontent">

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

            <div id="daftar" class="subcontent" style="display: none">

                    <table class="stdtable" id="dyntable">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php $no= 1; ?>
                            @foreach($data_petugas as $item)


                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        @if($item->active == 1)

                                            {{ "Aktif" }}

                                        @else

                                            {{ "Non active" }}

                                        @endif
                                    </td>

                                    {!! Form::open(['route' => 'do_update_petugas']) !!}
                                    {!! Form::hidden('id', $item->id) !!}

                                    <td>
                                        <select name="active" onchange="this.form.submit();">
                                            <option>--pilihan--</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Non aktif</option>
                                        </select>
                                    </td>
                                    {!! Form::close() !!}
                                </tr>

                            <?php $no++; ?> 

                            @endforeach

                        </tbody>
                    </table>
            </div>
        
        <br /><br />
        
    </div><!--contentwrapper-->



@endsection