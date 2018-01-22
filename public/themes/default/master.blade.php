<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $theme->get('description') }}">
    <meta name="keywords" content="{{ $theme->get('keywords') }}">
    <meta name="author" content="{{ $theme->get('author') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @foreach($theme->get('feedLink',[]) as $feedLink)
        <link rel="alternate" type="{{ $feedLink['cType'] }}" href="{{ $feedLink['url'] }}" title="Feed - {{ $theme->get('title') }}">
    @endforeach
    <link rel="shortcut icon" href="{{ $theme->url("ico/favicon.png") }}">
    <title>{{ $theme->get('title') }}</title>

    <meta property="og:site_name" content="Newportal Platform">
    <meta property="og:title" content="{{ $theme->get('title') }}">
    <meta property="og:description" content="{{ $theme->get('description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ URL::full() }}">
    <meta property="og:image" content="@if(!empty($theme->get('image'))){{ $theme->get('image') }}@else{{ $theme->url("img/newportal_webpage.png") }}@endif">

    <!-- Bootstrap core CSS -->
    <link href="{{ $theme->url("vendor/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Custom styles -->
    <link href="{{ $theme->url("css/grayscale-01264.css") }}" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="{{ $theme->url("vendor/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    @yield('style')
    @stack('style')

    {!! $theme->style() !!}
</head>

<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

    @yield('navigation')

    @yield('content')
    <br />
    @section('footer')

    @show

    <!-- jQuery -->
    <script src="{{ $theme->url("vendor/jquery/jquery.js") }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ $theme->url("vendor/bootstrap/js/bootstrap.min.js") }}"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Theme JavaScript -->
    <script src="{{ $theme->url("js/grayscale.js") }}"></script>

    @yield('scripts')
    @stack('scripts')

    {!! $theme->js() !!}
</body>
</html>