@extends('layouts.master')
@section('body')
<div class="wrapper">
    <!-- Header -->
    @include('layouts.header')

    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        @yield('breadcrumb')

        <!-- Main content -->
        <section class="content">

            <!-- Your Page Content Here -->
            @yield('content')

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    @include('layouts.footer')

    <!-- Control Sidebar -->
    @include('layouts.controlsidebar')

    <!-- Add the sidebar's background. -->
    <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
@endsection