{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__("Utenti"),'/admin/users')->add(__("Aggiorna utenti"))
        ->setTcrumb($user->name)
        ->render() !!}
@stop


@section('content')
<!-- Main content -->
<section class="content">
    @include('ui.messages')
    <div class="row">
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">{{ __("Dati obbligatori") }}</a></li>
                    <li><a href="#Autenticazione" data-toggle="tab">{{ __("Altri dati") }}</a></li>
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($user, ['action' => $action,'class' => 'form-horizontal']) !!}

                            {!! Form::slText('first_name','Nome') !!}
                            {!! Form::slText('last_name','Cognome') !!}
                            {!! Form::slEmail('email','Email') !!}
                            {!! Form::slPassword('password','Password') !!}
                            {!! Form::slPassword('password_confirmation','Conferma Password') !!}
                            {!! Form::slSubmit('Salva') !!}

                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="Autenticazione">
                        {!! Form::model($user, ['action' => $action,'class' => 'form-horizontal']) !!}

                            {!! Form::slText('username','Username') !!}
                            {!! Form::slDate('data_nascita','Data di nascita',$user->data_nascita) !!}
                            {!! Form::slSelect('country_id','Nazione',$countries) !!}
                            {!! Form::slSelect2('city_id','Comune',$cityOptions,$user->city_id,'/admin/users/cities/') !!}
                            {!! Form::slText('indirizzo','Indirizzo') !!}
                            {!! Form::slText('telefono','Telefono') !!}
                            {!! Form::slSelect('status_id','Stato',config('newportal.status_user')) !!}
                            {!! Form::slTextarea('note','Note') !!}
                            {!! Form::slSubmit('Salva') !!}

                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{$user->getAvatar()}}" alt="User profile picture">

                    <h3 class="profile-username text-center">{{$user->name}}</h3>

                    <p class="text-muted text-center">{{$user->email}}</p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>{{ __("Gruppi") }}</b> <a class="pull-right">{{$numGroups ?? 0}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ __("Organizzazioni") }}</b> <a class="pull-right">{{$numOrgs ?? 0}}</a>
                        </li>
                    </ul>
                    @if($user->id)
                        <p><a href="#" class="btn btn-default btn-block selectAvatar" data-id="{!! $user->id !!}"><i class="fa fa-camera"></i> {{ __("Cambia Foto") }}</a></p>
                        <a href="/admin/users/{!! $user->id !!}" class="btn btn-primary btn-block"><b>{{ __("Profilo") }}</b></a>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>

            <!-- /.box -->
            @if(array_get(cache('settings'), '2fa_activation'))
                <!-- 2FA -->
                <div class="box box-primary">
                    <div class="box-body box-profile">

                        <h3 class="profile-username text-center">2FA</h3>
                        <p class="text-muted text-center">Autenticazione a 2 fattori</p>
                        @if(!empty($user->google2fa_secret))
                            {!! Form::open(['url'=>url('/admin/users/2fa/'.$user->id),'class' => 'form-horizontal']) !!}
                                {!! Form::label('password','Password corrente', ['class' => 'control-label']) !!}
                                {!! Form::slText('password') !!}

                                <div class="pull-center">
                                    <input class="disattiva2FA btn btn-danger btn-block" type="submit" value="Disattiva 2Fa">
                                </div>

                            {!! Form::close() !!}
                        @else
                            <a href="/admin/users/2fa/{!! $user->id !!}" class="btn btn-default btn-block" style="margin-top:10px;"><i class="fa fa-key"></i> <strong>{{ __("Abilita") }}</strong></a>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            @endif

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@include('users.avatar')
@stop

@section('scripts')
    <script>

        //Select avatar Modal
        $('#selectAvatarModal').on('shown.bs.modal', function(){
        });

        $(".selectAvatar").click(function() {
            $('#selectAvatarModal').modal('toggle');
            $('#avatarForm').prop('action', '{{ url(Request::path().'/avatar/') }}');
        });

        $("input:file").change(function (){
            var label = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
                //input = $(this).parents('.input-group').find(':text');
                //$(".filename").val(label);
            $(".filename").prop('value',label);
            $(".avatarDefault").prop('checked', false); // Unchecks it
        });

        $(".avatarDefault").click( function(){
            if( $(this).is(':checked') ) {
                $(".filename").prop('value',null);
            };
        });
        $('.disattiva2FA').on('click', function(){
            return (confirm("Sei sicuro disattivare l'autenticazione 2FA?"));
        });

    </script>
@stop
