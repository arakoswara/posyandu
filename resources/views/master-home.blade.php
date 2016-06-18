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

    </head>
    <body class="withvernav">
    
        <div class="bodywrapper">

            @include('partials-home/header')

            @include('partials-home/sidebar')

            <div class="centercontent">
            
                @yield('content')

            </div>
        </div>
    </body>
</html>
