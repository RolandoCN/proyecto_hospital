<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    {{-- Plantilla --}}
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('../../plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('../../dist/css/adminlte.min.css')}}">

    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}

</head>
<body class="hold-transition sidebar-mini">
    <div id="app">
        
       <!-- wrapper -->
      <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
           
          </ul>
      
          <!-- Right navbar links -->
          <ul class="navbar-nav ml-auto">
           
      
            <!-- Messages Dropdown Menu -->
            <li class="nav-item dropdown">
              <a class="nav-link" data-toggle="dropdown" href="#"   style="color:black">
               
                
                {{ Auth::user()->name }} 
                <span class=" fa fa-angle-down"></span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Perfil</a>
                <div class="dropdown-divider"></div>
                <a  href="{{ route('logout') }}" class="dropdown-item dropdown-footer" 
                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fa fa-sign-out pull-right"></i>Cerrar Sesión
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>

              </div>
            </li>
            
           
           
          </ul>
        </nav>
        <!-- /.navbar -->
  
        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <!-- Brand Logo -->
          <a href="../../index3.html" class="brand-link">
            <img src="../../dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
          </a>
      
          <!-- Sidebar -->
          <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                <img src="../../dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }} </a>
              </div>
            </div>
      
            <!-- Sidebar Menu -->
            <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
              
                <li class="nav-item menu">
                  <a href="#" class="nav-link ">
                    <i class="nav-icon far fa-plus-square"></i>
                    <p>
                      Extras
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    @php
                      $ruta="test"
                    @endphp
                    <li class="nav-item">
                      <a href="{{url($ruta)}}" class="nav-link active" onclick="activo(1)" id="1">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Test</p>
                      </a>
                    </li>
                   
                    <li class="nav-item">
                      <a href="/test" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Blank Page</p>
                      </a>
                    </li>
                    
                  </ul>
                </li>


                {{-- <li class="nav-item menu">
                  <a href="#" class="nav-link active">
                    <i class="nav-icon far fa-plus-square"></i>
                    <p>
                      Extras
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                   
                    <li class="nav-item">
                      <a href="../test2" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Test</p>
                      </a>
                    </li>
                   
                    <li class="nav-item">
                      <a href="../examples/blank2.html" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Blank Pagex</p>
                      </a>
                    </li>
                    
                  </ul>
                </li> --}}
                
                
              </ul>
            </nav>
            <!-- /.sidebar-menu -->
          </div>
          <!-- /.sidebar -->
        </aside>
      
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
          @yield('content')
        </div>
        <!-- /.content-wrapper -->
  
        <footer class="main-footer">
          <div class="float-right d-none d-sm-block">
            <b>Version</b> 3.2.0
          </div>
          <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
        </footer>
  
        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
          <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
      </div>
      <!-- ./wrapper -->

    </div>

    <!-- jQuery -->
    <script src="{{ asset('../../plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('../../plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('../../dist/js/adminlte.min.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('../../dist/js/demo.js')}}"></script>

    <script>
      function activo(text){
        alert(text)
        $('#1').addClass('active')
      }
    </script>
</body>
</html>
