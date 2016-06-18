@extends('master-home')

@section('content')

<div class="pageheader">

    <h1 class="pagetitle">Data Balita</h1>

    <span class="pagedesc">Data balita posyandu MAWAR</span>
    
    <ul class="hornav">
        <li class="current"><a href="#inbox">Detail Data Balita</a></li>

        <li><a href="#compose">Periksa Balita</a></li>
    </ul>

</div><!--pageheader-->

<div id="contentwrapper" class="contentwrapper">
     
    <div id="inbox" class="subcontent">
     
        <div class="msghead">

            <div class="col-md-6">
                
                <h4>Data Detail Balita</h4>

                <hr class="garis">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No. Registrasi</th>
                            <th> : </th>
                            <th> {{ $data_balita->no_reg }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nama Balita</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_balita }}</td>
                        </tr>

                        <tr>
                            <td>Tanggal Lahir</td>
                            <td> : </td>
                            <td>{{ $data_balita->tgl_lahir }}</td>
                        </tr>

                        <tr>
                            <td>Jenis Kelamin</td>
                            <td> : </td>
                            <td>{{ $data_balita->jenis_kelamin }}</td>
                        </tr>

                        <tr>
                            <td>Nama Ayah</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_ayah }}</td>
                        </tr>

                        <tr>
                            <td>Nama Ibu</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_ibu }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-md-6">

                <h4>Riwayat Pemeriksaan</h4>

                <hr class="garis">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal Periksa</th>
                            <th> : </th>
                            <th> {{ $data_balita->no_reg }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Berat Badan</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_balita }}</td>
                        </tr>

                        <tr>
                            <td>Tinggi Badan</td>
                            <td> : </td>
                            <td>{{ $data_balita->tgl_lahir }}</td>
                        </tr>

                        <tr>
                            <td>BBU / U</td>
                            <td> : </td>
                            <td>{{ $data_balita->jenis_kelamin }}</td>
                        </tr>

                        <tr>
                            <td>TB / U</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_ayah }}</td>
                        </tr>

                        <tr>
                            <td>BB / TB</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_ayah }}</td>
                        </tr>

                        <tr>
                            <td>Energi yang dibutuhkan</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_ibu }}</td>
                        </tr>

                         <tr>
                            <td>Protein</td>
                            <td> : </td>
                            <td>{{ $data_balita->nama_ibu }}</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="col-md-12">

                <hr class="garis">
                
                <h3>Grafik Pemeriksaan Balita</h3>

                <hr class="garis">

            </div>

            
            <span class="clearall"></span>
        </div><!--msghead-->        
                    
    </div>

    <div id="compose" class="subcontent" style="display: none">
        
        <h3>Form Pemeriksaan Balita</h3>

        <hr>

        {!! Form::open(['route' => 'do_periksa_balita']) !!}

            <div class="col-md-6">
                
                <div class="form-group">
                    {!! Form::label('tgl_periksa', 'Tanggal Periksa') !!}

                    {!! Form::text('tgl_periksa', null, ['class' => 'form-control']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('berat_badan', 'Berat Badan') !!}

                    {!! Form::text('berat_badan', null, ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('tinggi_badan', 'Tinggi Badan') !!}

                    {!! Form::text('tinggi_badan', null, ['class' => 'form-control']) !!}
                </div>

            </div>

            <div class="col-md-12">
                
                <button type="submit" class="btn btn-primary">SIMPAN</button>
            </div>

        {!! Form::close() !!}
    </div>
</div><!--contentwrapper-->

@endsection