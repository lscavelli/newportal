<!DOCTYPE html>
<!--  -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $page_title ?? "Admin Dashboard" }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset("/node_modules/bootstrap/dist/css/bootstrap.min.css") }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset("/node_modules/font-awesome/css/font-awesome.min.css") }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset("/node_modules/ionicons/dist/css/ionicons.min.css") }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset("/node_modules/admin-lte/dist/css/AdminLTE.min.css") }}">
  <!-- AdminLTE Skins -->
  <link rel="stylesheet" href="{{ asset("/node_modules/admin-lte/dist/css/skins/skin-black.min.css") }}">
  <link rel="stylesheet" href="{{ asset("/css/custom.css") }}">
  @yield('style')
  @stack('style')
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-black sidebar-mini">
  @yield('body')
</body>
<!-- REQUIRED JS SCRIPTS -->
<!-- jQuery 2.2.4 -->
<script src="{{ asset("/node_modules/jquery/dist/jquery.min.js") }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset("/node_modules/bootstrap/dist/js/bootstrap.min.js") }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset("/node_modules/admin-lte/dist/js/adminlte.min.js") }}"></script>
@yield('scripts')
@stack('scripts')
</body>
</html>
