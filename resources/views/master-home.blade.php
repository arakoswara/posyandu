<!DOCTYPE html>
<html>
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Posyandu</title>

        <link rel="stylesheet" href="{{ asset('asset-home/css/style.default.css') }}" type="text/css" />

        <link rel="stylesheet" href="{{ asset('asset-home/css/bootstrap.css') }}" type="text/css" />

        <script type="text/javascript" src="{{ asset('asset-home/js/plugins/jquery-1.7.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/plugins/jquery-ui-1.8.16.custom.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/plugins/jquery.cookie.js') }}"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/plugins/jquery.alerts.js') }}"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/plugins/jquery.uniform.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/custom/general.js') }}"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/custom/messages.js') }}"></script>

        <!--Amchart-->
        <link rel="stylesheet" type="text/css" href="{{ asset('asset-home/amcharts/style.css') }}">
        <script src="{{ asset('asset-home/amcharts/amcharts.js') }}" type="text/javascript"></script>
        <script src="{{ asset('asset-home/amcharts/serial.js') }}" type="text/javascript"></script>
        <script src="{{ asset('asset-home/amcharts/amchart.js') }}" type="text/javascript"></script>
        <script src="{{ asset('asset-home/amcharts/amchart2.js') }}" type="text/javascript"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/custom/tables.js') }}"></script>

        <script type="text/javascript" src="{{ asset('asset-home/js/plugins/jquery.dataTables.min.js') }}"></script>
          

    </head>
    <body class="withvernav">
    
        <div class="bodywrapper">

            @include('partials-home/header')

            @include('partials-home/sidebar')

            <div class="centercontent">
            
                @yield('content')

            </div>
        </div>

        <script type="text/javascript" charset="utf8" src="{{ asset('asset-home/js/datatables.js') }}"></script>
    </body>
</html>
