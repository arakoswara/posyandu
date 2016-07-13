@extends('master-admin')

@section('content')

	<!-- Section: boxes -->

	@if(!empty($data_riwayat))

    <section id="boxes" class="home-section paddingtop-80">

    	<div class="container">
    		<div class="row">

    			<div class="col-sm-4 col-md-4">
    				<div class="wow fadeInUp" data-wow-delay="0.2s">
    					<div class="box">

    						<br><br>

    						<a href="{{ route('get_pdf_pencarian_all', $data_balita->id) }}" class="btn btn-danger" style="color:white">
    							Download PDF
    						</a>

    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
	
		<div class="container">
			<div class="row">

				@foreach($data_riwayat as $item)

				<div class="col-sm-4 col-md-4">
					<div class="wow fadeInUp" data-wow-delay="0.2s">
						<div class="box">
							
							<i class="fa fa-check fa-3x circled bg-skin"></i>
							<h4 class="h-bold">Riwayat Pemeriksaan Balita</h4> 
							
							<div class="well well-trans">
							    <div class="wow fadeInRight" data-wow-delay="0.1s">

								    <ul class="lead-list">
								        <li>
								        	<span class="fa fa-check fa-2x icon-success"></span> 
								        	<span class="list">
								        		<strong>Tanggal Periksa</strong><br /> {{ $item->periksa->tgl_periksa }}
								        	</span>
								        </li>

								        <li>
								        	<span class="fa fa-check fa-2x icon-success"></span>
								        	<span class="list"><strong>Berat Badan</strong><br />
								        		{{ $item->periksa->berat_badan }} Kg
								        	</span>
								        </li>

								        <li>
								        	<span class="fa fa-check fa-2x icon-success"></span>
								        	<span class="list"><strong>Tinggi Badan</strong><br />
								        		{{ $item->periksa->tinggi_badan }} cm
								        	</span>
								        </li>

								        <li>
								        	<span class="fa fa-check fa-2x icon-success"></span>
								        	<span class="list"><strong>BB / U</strong><br />
								        		{{ $item->zbbu }} |

								        		@if($item->zbbu < -3)

								        		    {{ "Gizi Buruk" }}

								        		@elseif($item->zbbu >= -3 && $item->zbbu < -2)

								        		    {{ "Gizi Kurang" }}

								        		@elseif($item->zbbu >= -2 && $item->zbbu <= 2)

								        		    {{ "Gizi Baik" }}

								        		@elseif($item->zbbu > 2)

								        		    {{ "Gizi Lebih" }}

								        		@endif

								        	</span>
								        </li>

								        <li>
								        	<span class="fa fa-check fa-2x icon-success"></span>
								        	<span class="list"><strong>TB / U</strong><br />
								        		{{ $item->ztbu }} |

								        		@if($item->ztbu < -3)

								        		    {{ "Sangat Pendek" }}

								        		@elseif($item->ztbu >= -3 && $item->ztbu < -2)

								        		    {{ "Pendek" }}

								        		@elseif($item->ztbu >= -2 && $item->ztbu <= 2)

								        		    {{ "Normal" }}

								        		@elseif($item->ztbu > 2)

								        		    {{ "Tinggi" }}

								        		@endif

								        	</span>
								        </li>

								        <li>
								        	<span class="fa fa-check fa-2x icon-success"></span>
								        	<span class="list"><strong>BB / TB</strong><br />
								        		{{ $item->zbbtb }} |

								        		@if($item->zbbtb < -3)

								        		    {{ "Sangat Kurus" }}

								        		@elseif($item->zbbtb >= -3 && $item->zbbtb < -2)

								        		    {{ "Kurus" }}

								        		@elseif($item->zbbtb >= -2 && $item->zbbtb <= 2)

								        		    {{ "Normal" }}

								        		@elseif($item->zbbtb > 2)

								        		    {{ "Gemuk" }}

								        		@endif

								        	</span>
								        </li>

								        <li>
								        	<span class="fa fa-check fa-2x icon-success"></span>
								        	<span class="list"><strong>Kebutuhan Energi & Protein / hari </strong><br />
								        		{{ $item->energi }} kkal | {{ $item->protein }} gr
								        	</span>
								        </li>
								        
								    </ul>
								    
							    </div>
							</div> 
						</div>
					</div>
				</div>


				@endforeach
				
			
			</div>
			
			<div class="row">
				<div class="co-md-12">
					{!! $data_riwayat->render() !!}
				</div>
			</div>
		</div>

	</section>
	<!-- /Section: boxes -->

	@else

    <section id="intro" class="intro">
        <div class="intro-content">
            <div class="container" style="min-height:420px">
                <div class="row">

                    <div class="col-lg-12">

                         
                        <div class="wow fadeInDown" data-wow-offset="0" data-wow-delay="0.1s">
                            <h1 class="h-ultra">Maaf, tidak ada data ditemukan</h1>
                            <h2 class="h-ultra">Posyandu Melati</h2>
                        </div>
                        <div class="wow fadeInUp" data-wow-offset="0" data-wow-delay="0.1s">
                            <h4 class="h-light">Melayani Dengan Sepenuh<span style="color:red"> <i class="fa fa-heart"></i> </span></h4>
                        </div>

                        <div class="well well-trans">
                            <div class="wow fadeInRight" data-wow-delay="0.1s">

                            <ul class="lead-list">
                                <li><span class="fa fa-check fa-2x icon-success"></span> <span class="list"><strong>Maaf, ID / No. Registrasi Salah</strong><br />
                                Maaf, ID / No. Registrasi Balita anda tidak terdaftar atau salah</span></li>
                                
                            </ul>

                            </div>
                        </div>       
                    </div>
                </div>
            </div>
        </div>
    </section>


	@endif

@endsection