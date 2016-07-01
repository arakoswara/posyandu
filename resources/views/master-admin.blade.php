<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Posyandu Melati</title>
	
    <!-- css -->
    <link href="{{ asset('asset/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('asset/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="{{ asset('asset/plugins/cubeportfolio/css/cubeportfolio.min.css') }}">
	<link href="{{ asset('asset/css/nivo-lightbox.css') }}" rel="stylesheet" />
	<link href="{{ asset('asset/css/nivo-lightbox-theme/default/default.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('asset/css/owl.carousel.css') }}" rel="stylesheet" media="screen" />
    <link href="{{ asset('asset/css/owl.theme.css') }}" rel="stylesheet" media="screen" />
	<link href="{{ asset('asset/css/animate.css') }}" rel="stylesheet" />
    <link href="{{ asset('asset/css/style.css') }}" rel="stylesheet">

	<!-- boxed bg -->
	<link id="bodybg" href="bodybg/bg1.css" rel="stylesheet" type="text/css" />
	<!-- template skin -->
	<link id="t-colors" href="color/default.css" rel="stylesheet">

	<!--Amchart-->
	<link rel="stylesheet" type="text/css" href="{{ asset('asset-home/amcharts/style.css') }}">
	<script src="{{ asset('asset-home/amcharts/amcharts.js') }}" type="text/javascript"></script>
	<script src="{{ asset('asset-home/amcharts/serial.js') }}" type="text/javascript"></script>
	<script src="{{ asset('asset-home/amcharts/amchart.js') }}" type="text/javascript"></script>


</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-custom">

	<div id="wrapper">

		@include('partial-admin/header')

		@yield('content')

		{{-- @include('partial-admin/footer') --}}


	</div>

	<!-- Core JavaScript Files -->
    <script src="{{ asset('asset/js/jquery.min.js') }}"></script>	 

    <script src="{{ asset('asset/js/bootstrap.min.js') }}"></script>

    <script src="{{ asset('asset/js/jquery.easing.min.js') }}"></script>

	<script src="{{ asset('asset/js/wow.min.js') }}"></script>

	<script src="{{ asset('asset/js/jquery.scrollTo.js') }}"></script>
	<script src="{{ asset('asset/js/jquery.appear.js') }}"></script>
	<script src="{{ asset('asset/js/stellar.js') }}"></script>
	<script src="{{ asset('asset/plugins/cubeportfolio/js/jquery.cubeportfolio.min.js') }}"></script>
	<script src="{{ asset('asset/js/owl.carousel.min.js') }}"></script>
	<script src="{{ asset('asset/js/nivo-lightbox.min.js') }}"></script>
    <script src="{{ asset('asset/js/custom.js') }}"></script>

</body>
</html>