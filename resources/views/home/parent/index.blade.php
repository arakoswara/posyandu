@extends('master-admin')

@section('content')

		<!-- Section: boxes -->
	    <section id="boxes" class="home-section paddingtop-80">
		
			<div class="container">
				<div class="row">
					<div class="col-sm-6 col-md-6">
						<div class="wow fadeInUp" data-wow-delay="0.2s">
							<div class="box">
								
								<i class="fa fa-check fa-3x circled bg-skin"></i>
								<h4 class="h-bold">Data Balita</h4>
								
								<div class="well well-trans">
								    <div class="wow fadeInRight" data-wow-delay="0.1s">

								    <p>
									    <ul class="lead-list">
									        <li>
									        	<span class="fa fa-lock fa-2x icon-success"></span> 
									        	<span class="list">
									        		<strong>ID / No. Registrasi Balita</strong><br /> {{ $data_balita->no_reg }}
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-user fa-2x icon-success"></span>
									        	<span class="list"><strong>Nama Balita</strong><br />
									        		{{ $data_balita->nama_balita }}
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-calendar fa-2x icon-success"></span>
									        	<span class="list"><strong>Tanggal Lahir</strong><br />
									        		{{ $data_balita->tgl_lahir }}
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-child fa-2x icon-success"></span>
									        	<span class="list"><strong>Jenis Kelamin</strong><br />
									        		
									        		@if($data_balita->jenis_kelamin == 'P')

									        			{{ "PEREMPUAN" }}

									        		@else

									        			{{ "LAKI-LAKI" }}

									        		@endif

									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-wheelchair fa-2x icon-success"></span>
									        	<span class="list"><strong>Nama Ayah</strong><br />
									        		{{ $data_balita->nama_ayah }}
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-wheelchair fa-2x icon-success"></span>
									        	<span class="list"><strong>Nama Ibu</strong><br />
									        		{{ $data_balita->nama_ibu }}
									        	</span>
									        </li>
									        
									    </ul>
								    </p>

								    </div>
								</div> 
							</div>
						</div>
					</div>

					<div class="col-sm-6 col-md-6">
						<div class="wow fadeInUp" data-wow-delay="0.2s">
							<div class="box">
								
								<i class="fa fa-check fa-3x circled bg-skin"></i>
								<h4 class="h-bold">History Pemeriksaan Balita</h4>
								
								<div class="well well-trans">
								    <div class="wow fadeInRight" data-wow-delay="0.1s">

								    <p>
									    <ul class="lead-list">
									        <li>
									        	<span class="fa fa-check fa-2x icon-success"></span> 
									        	<span class="list">
									        		<strong>Tanggal Periksa</strong><br /> {{ $data_pencarian->periksa->tgl_periksa }}
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-check fa-2x icon-success"></span>
									        	<span class="list"><strong>Berat Badan</strong><br />
									        		{{ $data_pencarian->periksa->berat_badan }} Kg
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-check fa-2x icon-success"></span>
									        	<span class="list"><strong>Tinggi Badan</strong><br />
									        		{{ $data_pencarian->periksa->tinggi_badan }} cm
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-check fa-2x icon-success"></span>
									        	<span class="list"><strong>BBU / U</strong><br />
									        		{{ $data_pencarian->zbbu }}
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-check fa-2x icon-success"></span>
									        	<span class="list"><strong>TBU / U</strong><br />
									        		{{ $data_pencarian->ztbu }}
									        	</span>
									        </li>

									        <li>
									        	<span class="fa fa-check fa-2x icon-success"></span>
									        	<span class="list"><strong>Energi & Protein</strong><br />
									        		{{ $data_pencarian->protein }} | {{ $data_pencarian->energi }}
									        	</span>
									        </li>
									        
									    </ul>
								    </p>

								    </div>
								</div> 
							</div>
						</div>
					</div>
				
				</div>
			</div>


			<div class="container">
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="wow fadeInUp" data-wow-delay="0.2s">
							<div class="box">

								<i class="fa fa-check fa-3x circled bg-skin"></i>
								<h4 class="h-bold">Grafik Hasil Pemeriksaan</h4>

								@if (empty($grafik_score))
								    
								    <h4> Tidak ada riwayat pemeriksaan</h4>

								@else

								    <div id="grafikBalita" style="width:100%; height: 400px;"></div>

								@endif

							</div>
						</div>
					</div>
				</div>
			</div>

		</section>
		<!-- /Section: boxes -->

		<script>

		    var chartData = <?php  echo $grafik_score; ?>

		</script>

@endsection