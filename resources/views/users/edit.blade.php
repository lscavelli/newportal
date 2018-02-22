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
                            <div class="form-group">
                                <label for="nome" class="col-sm-2 control-label">{{ __("Nome") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text('nome',null,['class' => 'form-control', 'placeholder'=> __("Nome")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cognome" class="col-sm-2 control-label">{{ __("Cognome") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text('cognome',null,['class' => 'form-control', 'placeholder'=> __("Cognome")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <?php $disabled = []; if(!(Auth()->user()->isUserManager()) and $user->id) $disabled=['disabled'=>'']; ?>
                                    {!! Form::email('email',null, array_merge(['id' => 'email','class' => 'form-control', 'placeholder'=> "Email"],$disabled)) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password" class="col-sm-2 control-label">Password</label>
                                <div class="col-sm-10">
                                    {!! Form::password('password',['id' => 'password','class' => 'form-control', 'placeholder'=> "Password"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation" class="col-sm-2 control-label">{{ __("Conferma Password") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::password('password_confirmation',['id' => 'password_confirmation','class' => 'form-control', 'placeholder'=> __("Conferma Password")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane -->
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="Autenticazione">
                        {!! Form::model($user, ['action' => $action,'class' => 'form-horizontal']) !!}
                            <div class="form-group">
                                <label for="username" class="col-sm-2 control-label">Username</label>
                                <div class="col-sm-10">
                                    {!! Form::text('username',null,['class' => 'form-control', 'placeholder'=> "Username"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="data_nascita" class="col-sm-2 control-label">{{ __("Data di nascita") }}</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <?php $datanascita = (isset($user->data_nascita)?$user->data_nascita->format('d/m/Y'): null); ?>
                                        {!! Form::text('data_nascita',$datanascita ,['class' => 'form-control pull-right date-picker', 'placeholder'=> __("Data di nascita"), 'id'=>'data_nascita']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="country_id" class="col-sm-2 control-label">{{ __("Nazione") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::select('country_id', $countries , \Request::input('country_id'), ['class' => "form-control input-sm", 'id'=>"country_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="city_id" class="col-sm-2 control-label">{{ __("Comune") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::select('city_id', $cityOptions , $user->city_id, ['class' => "js-example-basic-single js-states form-control", 'id'=>"city_id", 'style'=>"width: 100%", 'aria-hidden'=>"true"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="indirizzo" class="col-sm-2 control-label">{{ __("Indirizzo") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::text('indirizzo',null,['class' => 'form-control', 'placeholder'=> __("Indirizzo")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="telefono" class="col-sm-2 control-label">{{ __("Telefono") }}</label>

                                <div class="col-sm-10">
                                    {!! Form::text('telefono',null,['class' => 'form-control', 'placeholder'=> __("Telefono")]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="status_id" class="col-sm-2 control-label">{{ __("Stato") }}</label>
                                <div class="col-sm-10">
                                    {!! Form::select('status_id', config('newportal.status_user') , \Request::input('xpage'), ['class' => "form-control input-sm", 'id'=>"status_id"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="note" class="col-sm-2 control-label">Note</label>

                                <div class="col-sm-10">
                                    {!! Form::textarea('note',null,['class' => 'form-control', 'placeholder'=> "Note"]) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-danger">Submit</button>
                                </div>
                            </div>
                        </form>
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
                            <b>{{ __("Gruppi") }}</b> <a class="pull-right">{{$numGroups or 0}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>{{ __("Organizzazioni") }}</b> <a class="pull-right">{{$numOrgs or 0}}</a>
                        </li>
                    </ul>
                    @if($user->id)
                        <p><a href="#" class="btn btn-default btn-block selectAvatar" data-id="{!! $user->id !!}"><i class="fa fa-camera"></i> {{ __("Cambia Foto") }}</a></p>
                        <a href="/admin/users/profile/{!! $user->id !!}" class="btn btn-primary btn-block"><b>{{ __("Profilo") }}</b></a>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@include('users.avatar')
@stop
@section('style')
    <link rel="stylesheet" href="{{ asset("/node_modules/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css") }}">
    <link rel="stylesheet" href="{{ asset("/css/custom.datetimepicker.css") }}">
    {{ Html::style('/node_modules/select2/dist/css/select2.min.css') }}
    <style>
        .skin-purple-light, .main-header, .navbar {
            background-color: #2C3E50!important;
        }
        .skin-blue, .main-header, .logo {
            background-color: #FFF!important;
            color: #333!important;
        }
    </style>
@stop
@section('scripts')
    <script src="{{ asset("/node_modules/moment/min/moment.min.js") }}"></script>
    <script src="{{ asset("/node_modules/moment/locale/it.js") }}"></script>
    <script src="{{ asset("/node_modules/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js") }}"></script>
    {{ Html::script('/node_modules/select2/dist/css/select2.min.js') }}
    <script>
        //moment.locale('it');
        //Date Time Picker
        if ($('.date-time-picker')[0]) {
            $('.date-time-picker').datetimepicker();
        }
        //Time
        if ($('.time-picker')[0]) {
            $('.time-picker').datetimepicker({
                format: 'LT'
            });
        }
        //Date
        if ($('.date-picker')[0]) {
            $('.date-picker').datetimepicker({
                format: 'DD/MM/YYYY'
            });
        }
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

        $(".js-example-basic-single").select2({
            minimumInputLength: 3,
            ajax: {
                url: '/admin/users/cities/',
                data: function (params) {
                    var query = {
                        q: params.term,
                    }
                    return query;
                },
                processResults: function (data) {
                    return {
                        results: data.items
                    };
                },
                dataType: 'json',
                delay: 250
            }
        });

    </script>
@stop
