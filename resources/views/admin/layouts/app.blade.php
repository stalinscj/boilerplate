<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name') }} | @yield('title')</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">

  {{-- Plugins CSS --}}
  @stack('plugins-css')

  <!-- AdminLTE -->
  <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/adminlte.min.css') }}">

  {{-- CSS --}}
  @stack('css')

</head>
<body class="hold-transition sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">
    @include('admin.layouts._navbar')

    @include('admin.layouts._sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>@yield('title-header')</h1>
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        @yield('content')
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    @include('admin.layouts._footer')

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{ asset('vendor/jquery/js/jquery.min.js') }}"></script>

  <!-- Bootstrap 4 -->
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  {{-- Plugins Scripts --}}
  @stack('plugins-js')

  <!-- AdminLTE App -->
  <script src="{{ asset('vendor/adminlte/js/adminlte.min.js') }}"></script>

  {{-- Scripts --}}
  @stack('js')
</body>
</html>
