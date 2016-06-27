<!DOCTYPE html>
<html>
<head>
    <title></title>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script type="text/javascript" src="{{ asset('asset-home/bootstrap/moment.min.js') }}"></script>
      <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="{{ asset('asset-home/bootstrap/bootstrap-datetimepicker.min.js') }}"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
      <link rel="stylesheet" href="{{ asset('asset-home/bootstrap/css/bootstrap-datetimepicker.min.css') }}" />
</head>
<body>
    <div class="container">
        <div class="row">
            <div class='col-sm-6'>
                <input type="text" class="form-control" id="datetimepicker" />
            </div>
            
        </div>
    </div>

    <script type="text/javascript">
        $(function () {
            $('#datetimepicker').datetimepicker({
                format : 'DD-MM-YYYY'
            });
        });
    </script>
</body>
</html>