@extends('master')

@section('navigation')
        <!-- Navigation -->
<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-main-collapse">
                Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand page-scroll" href="#page-top">
                <i class="fa fa-play-circle"></i> <span class="light">LFG.</span> Scavelli
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling - collapse navbar-collapse -->
        <div class="collapse navbar-collapse navbar-right navbar-main-collapse">
            <ul class="nav navbar-nav">
                <!-- Hidden li included to remove active class from about link when scrolled up past about section -->
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li>
                    <a href="/welcome">Welcome</a>
                </li>
                <li>
                    <a href="/articles?category=1">Articles</a>
                </li>
                @if (Route::has('login'))
                    @if (Auth::check())
                        <li><a href="{{ url('/admin/dashboard') }}">Home</a></li>
                    @else
                        <li><a href="{{ url('/login') }}">Login</a></li>
                    @endif
                @endif
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>
@endsection

@section('content')
    <!-- Content web Section -->
    <div class="container" Style="padding-top: 120px;">
        <div class="row">
            <div class="col-lg-4 menusx" style="height: auto; font-size: 0.9em; width: 28em;">
                {!! $theme->getFrame('navcontent') !!}
            </div>
            <div class="col-lg-8">
                {!! $theme->getFrame('content') !!}
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-above" style="position: relative">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h3>About</h3>
                        <p><a href="/features">Features</a>
                            <br><a href="/video">Video</a>
                            <br><a href="/installation">Installation</a>
                            <br><a href="/license">License</a></p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>Around the Web</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="#" class="btn-social btn-outline white"><span class="sr-only">Facebook</span><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline white"><span class="sr-only">Google Plus</span><i class="fa fa-fw fa-google-plus"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline white"><span class="sr-only">Twitter</span><i class="fa fa-fw fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline white"><span class="sr-only">Linked In</span><i class="fa fa-fw fa-linkedin"></i></a>
                            </li>
                            <li>
                                <a href="#" class="btn-social btn-outline white"><span class="sr-only">Dribble</span><i class="fa fa-fw fa-dribbble"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>About this Site</h3>
                        <p>Questo tema si basa su template open source bootstrap creati da <a href="http://startbootstrap.com">Start Bootstrap</a>.</p>
                        {!! $theme->getFrame('footer') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; Website 2017
                    </div>
                </div>
            </div>
        </div>
    </footer>
@if(auth()->check())
    @include('ui.configPortlet')
@endif
@endsection

@section('style')
    <style>
        @media only screen and (min-width: 768px) {
            .navbar-custom {
                background: #2C3E50;
            }
        }
    </style>
@endsection
@section('scripts')
@endsection