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
                            <a class="page-scroll" href="#about">About</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#download">Download</a>
                        </li>
                        <li>
                            <a class="page-scroll" href="#contact">Contact</a>
                        </li>
                        <li>
                            <a href="/articles">Articles</a>
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
    <!-- Intro Header -->
    <header class="intro">
        <div class="intro-body" Style="overflow: hidden;">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <h1 class="brand-heading">NewPortaL</h1>
                        <p class="intro-text">A new simple and flexible web portals generator.
                            <br>Created and maintained by Scavelli.</p>
                        <a href="#about" class="btn btn-circle page-scroll">
                            <i class="fa fa-angle-double-down animated"></i>
                        </a>
                    </div>
                </div>
                <br /><br />
                <div class="row" Style="text-align: center">
                    <div class="col-lg-12">
                        <div class="intro-banner">
                            <img src="{{ $theme->url("img/newportal.png") }}" alt="newportal app" Style="box-shadow: 0 20px 50px 0 rgba(0,0,0,.05), 0 4px 8px 0 rgba(0,0,0,.18), 0 20px 40px 0 rgba(0,0,0,.21); border-radius: 6px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section id="about" class="text-center">
        <div class="about-section">
            <div class="container">
                <div class="col-lg-8 col-lg-offset-2">
                    {!! $theme->getFrame('about') !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Download Section -->
    <section id="download" class="content-section text-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    {!! $theme->getFrame('download') !!}
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="text-center">
        <div class="container">
            <div class="row" style="margin-bottom: 20px">
                <div class="col-lg-12">
                    <h2>Contact</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2">
                    @include('ui.messages')
                    <!-- To configure the contact form email address, go to mail/contact_me.php and update the email address in the PHP file on line 19. -->
                    <!-- The form should work on most web servers, but if the form is not working you may need to configure your web server differently. -->
                    <form name="sendMessage" id="contactForm" method="post" novalidate action="{{ url('/contactform') }}#contact">
                        @csrf
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" placeholder="Name" name="name" id="name">
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" placeholder="Email Address" name="email" id="email">
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="phone">Phone Number</label>
                                <input type="tel" class="form-control" placeholder="Phone Number" name="phone" id="phone">
                            </div>
                        </div>
                        <div class="row control-group">
                            <div class="form-group col-xs-12 floating-label-form-group controls">
                                <label for="message">Message</label>
                                <textarea rows="5" class="form-control" placeholder="Message" name="message" id="message"></textarea>
                            </div>
                        </div>
                        <br>
                        <div id="success"></div>
                        <div class="row">
                            <div class="form-group col-xs-12">
                                <button type="submit" class="btn btn-primary btn-lg">Send</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer')
    <!-- Footer -->
    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-4">
                        <h3>About</h3>
                        <p><a href="/features">Features</a>
                            <br><a href="/video">Video</a>
                            <br><a href="/installation">Installation</a>
                            <br><a href="/license">License</a>
                            <br><a href="/changelog">Changelog</a>
                        </p>
                    </div>
                    <div class="footer-col col-md-4">
                        <h3>Around the Web</h3>
                        <ul class="list-inline">
                            <li>
                                <a href="https://www.facebook.com/newportalcms/" class="btn-social btn-outline white"><span class="sr-only">Facebook</span><i class="fa fa-fw fa-facebook"></i></a>
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
@endsection
@section('scripts')
@endsection
