<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle ?? 'Default Title' }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <!-- Summernote -->
    <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="" src="dist/img/logo.gif" alt="logo" height="200"
                width="200">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                   <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item dropdown  mr-3">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        <span class="text-black">{{ auth()->user()->name ?? 'Guest' }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button id="logoutButton" class="dropdown-item text-danger" type="button">Logout</button>
                        </form>
                    </div>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
            
           
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-white-primary elevation-4">
            <!-- Brand Logo -->
            <h1 class="brand-link text-center">
                <span class="brand-text font-weight-bold">
                    <span style="color: #008080;">Café</span><span style="color: #FFC107;">Core</span>
                </span>
            </h1>

            <!-- Sidebar -->
            <div class="sidebar">
                
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->

                     <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('bahan') }}" class="nav-link {{ request()->routeIs('bahan') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-egg"></i>
                            <p>Bahan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('menu') }}" class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Menu</p>
                            <i class="fas fa-angle-left right"></i>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('kategori_menu') }}" class="nav-link {{ request()->routeIs('kategori_menu') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Kategori</p>
                              </a>
                            </li>  
                            <li class="nav-item">
                                <a href="{{ route('menu') }}" class="nav-link {{ request()->routeIs('menu') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Menu</p>
                              </a>
                            </li>  
                                                     
                          </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang_masuk') }}" class="nav-link {{ request()->routeIs('barang_masuk') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-download"></i>
                            <p>Barang Masuk</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('barang_keluar') }}" class="nav-link {{ request()->routeIs('barang_keluar') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-upload"></i>
                            <p>Barang Keluar</p>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>Costing
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                              <a href="{{ route('costing') }}" class="nav-link {{ request()->routeIs('costing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Costing(Gudang)</p>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="{{ route('costing2') }}" class="nav-link {{ request()->routeIs('costing2') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Costing(Café)</p>
                              </a>
                            </li>
                            <li class="nav-item">
                              <a href="{{ route('costing3') }}" class="nav-link {{ request()->routeIs('costing3') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Costing(SSG)</p>
                              </a>
                            </li>
                          </ul>
                    </li>

                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Standard Recipe
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('standard_recipe') }}" class="nav-link {{ request()->routeIs('standard_recipe') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Konversi Harga Bahan</p>
                              </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menu_pricing') }}" class="nav-link {{ request()->routeIs('menu_pricing') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Menu Pricing</p>
                              </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hpp_food') }}" class="nav-link {{ request()->routeIs('hpp_food') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>HPP Food</p>
                              </a>
                            </li>                            
                          </ul>
                    </li>

                    <li class="nav-item">
                        <a href="" class="nav-link">
                            <i class="nav-icon fas fa-hammer"></i>
                            <p>Menu Engineering
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('menu_engineering') }}" class="nav-link {{ request()->routeIs('menu_engineering') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                <p>Sales Report</p>
                              </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menu_engineering2') }}" class="nav-link {{ request()->routeIs('menu_engineering2') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                <p>Rekap Sales</p>
                              </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menu_engineering3') }}" class="nav-link {{ request()->routeIs('menu_engineering3') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                <p>Sales & Potentials</p>
                              </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('menu_engineering4') }}" class="nav-link {{ request()->routeIs('menu_engineering4') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                <p>Main Engineering</p>
                              </a>
                            </li>
                          </ul>
                    </li>

                    <li class="nav-header">Other</li>

                    <li class="nav-item">
                        <a href="{{ route('users') }}" class="nav-link {{ request()->routeIs('users') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">{{ $pageTitle ?? 'Default Title' }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                                <li class="breadcrumb-item active">{{ $pageTitle ?? 'Default Title' }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

           <!-- Scripts (ORDER MATTERS) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

    <!-- AdminLTE -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

    <!-- Summernote -->
    <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>

    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <!-- Daterangepicker -->
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>

    

    <!-- Page-specific JS -->
    <script>
$(document).ready(function() {
  $('#logoutButton').click(function(e) {
    e.preventDefault();

    Swal.fire({
      title: 'Are you sure?',
      text: "You want to logout?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // Submit the form programmatically
        $(this).closest('form').submit();
      }
    });
  });
});
</script>

    <script>
        $(window).on('load', function () {
            $(".preloader").fadeOut();
        });

        $('[data-widget="pushmenu"]').on('click', function(e) {
    e.preventDefault();
    $('body').toggleClass('sidebar-collapse');
});

$(function () {
  $('.dropdown-toggle').dropdown();
});


    </script>

        </body>

</html>
