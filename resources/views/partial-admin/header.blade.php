<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
   {{--  <div class="top-area" style="background-color:green;">
    </div> --}}

    <div class="container navigation">
    
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{{ route('home_index') }}">
                {{-- <img src="{{ asset('asset/img/logo.png') }}" alt="" width="150" height="40" /> --}}
                <h3>SPBP2</h3>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="{{ route('home_index') }}">Home</a></li>
            <li><a href="{{ route('login-form') }}">Login</a></li>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>