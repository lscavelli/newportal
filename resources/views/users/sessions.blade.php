@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista utenti',url("/admin/users"))
        ->add('Sessioni attive')
        ->render() !!}
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        @include('ui.messages')
        <div class="row">
            <div class="col-xs-12">
                <div class="box" style="padding-top: 20px;">
                    {!!
                        $list->columns(['user_id'=>'Utente','ip_address'=>'Ip','user_agent','last_activity'=>'Ultima attività','Azioni'])
                        ->showButtonNew(false)
                        ->showActions(false)
                        ->showSearch(false)
                        ->showXpage(false)
                        ->customizes('user_id',function($row){
                            if (!empty($row['user_id']))
                                return \App\Models\User::find($row['user_id'])->name;
                        })
                        ->customizes('last_activity',function($row){
                            return Carbon\Carbon::createFromTimestamp($row['last_activity'])->format('d-m-Y H:i');
                        })
                        ->customizes('Azioni',function($row){
                            return "<a href=\"#\" class=\"btn btn-danger btn-xs pull-right delete\" data-id=\"{$row['id']}\">Cancella</a>";
                        })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </section>
    <!-- /.content -->
@stop