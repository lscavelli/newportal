<!-- Main Header -->
  <header class="main-header">

    <!-- Logo -->
    <a href="{{ asset("/bower_components/AdminLTE/index2.html") }}" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">N<b>PL</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">NEW<b>Portal</b></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar-->
              <img src="{{ Auth::user()->getAvatar() }}" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs">{{Auth::user()->name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- The user image in the menu -->
              <li class="user-header">
                <img src="{{ Auth::user()->getAvatar() }}" class="img-circle" alt="User Image">

                <p>
                    {{Auth::user()->name}}
                    <small>Membro da  {{ Carbon\Carbon::parse(Auth::user()->created_at)->format('M Y') }}</small>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{ url('/admin/users/profile',Auth::user()->id) }}" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">

                  @if(!session()->has('user_r'))
                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                      Logout</a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                  @else
                    <a href="{{ url('admin/users/revert') }}" class="btn btn-default btn-flat">Ripristina Utente</a>
                  @endif

                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>