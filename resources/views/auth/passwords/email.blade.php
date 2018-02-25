@extends('auth.master')
@section('body')

    <div class="login-box">
        @include('ui.messages')
        <div class="login-logo">
            <a href="#"><b>Newportal</b> Platform</a>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Reset Password</p>

            <form action="{{ url('/password/email') }}" role="form" method="POST">
                @csrf
                <div class="form-group has-feedback">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required autofocus>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Invia il link per resettare la password</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

@endsection

