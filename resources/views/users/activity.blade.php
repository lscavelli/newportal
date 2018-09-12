@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add(__('Lista utenti'),url("/admin/users"))
        ->add('Attività'. $nameUser)
        ->setTcrumb($nameUser,__('Attività'))
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
                        $list->columns(['id','user_id'=>__('Utente'),'created_at'=>__('Data'),'description'=>__('Attività'),'ip_address'=>'Ip'])
                        ->showActions(false)
                        ->showButtonNew(false)
                        ->customizes('user_id',function($row) use($nameUser) {
                            if (!is_null($nameUser)) return $nameUser;
                            return \App\Models\User::find($row['user_id'])->name;})
                        ->customizes('created_at',function($row){
                            return Carbon\Carbon::parse($row['created_at'])->format('d-m-Y H:i');
                        })->render()
                    !!}
                </div> <!-- /.box -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </section>
    <!-- /.content -->
@stop