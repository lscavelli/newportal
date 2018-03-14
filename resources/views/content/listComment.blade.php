@extends('layouts.admin')

@section('breadcrumb')
{!! $breadcrumb->add('Lista Content','/admin/content')->add("Lista commenti")
    ->setTcrumb($nameContent)
    ->render()
!!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id','name'=>'nome','approved'=>'Stato','created_at'=>'Creato il','updated_at'=>'Aggiornato il'])
                    ->actions(['approved'=>'Passa ad approvato','notapproved'=>'Passa a non approvato'])
                     ->customizes('approved',function($row){
                        if ($row['approved']) {
                            $color = 'bg-green'; $state = 'Approvato';
                        } else {
                            $color = 'bg-danger'; $state = 'Non approvato';
                        }
                        return "<span class=\"pull-right-container\"><small class=\"label pull-right {$color}\">{$state}</small></span>";
                    })
                    ->customizes('updated_at',function($row){
                        return $row['updated_at']->format('d/m/Y');
                    })
                    ->customizes('created_at',function($row){
                        return $row['created_at']->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop