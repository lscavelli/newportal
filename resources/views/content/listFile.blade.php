{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Lista files')->render() !!}
@stop

@section('content')

    <!-- Main content -->
    @include('ui.messages')
    <div class="row">
        <div class="col-xs-12">
            <div class="box" style="padding-top: 20px;">
                {!!
                    $list->columns(['id'=>'Id','name'=>'Nome','file_name'=>'File','created_at'=>__('Creato il')])
                    ->addSplitButtons([
                        'file'=>'Nuovo file',
                        'image'=>'Nuova immagine',
                    ],false)
                    ->sortFields(['id','name','file_name'])
                    ->customizes('created_at',function($row){
                        return $row['created_at']->format('d/m/Y');
                    })->render()
                !!}
            </div> <!-- /.box -->
        </div> <!-- /.col -->
    </div> <!-- /.row -->
    <!-- /.content -->
@stop

@push('scripts')
    <script>
        $('.splitButtons li a').on('click', function(e){
            e.preventDefault();
            var height = 600;
            var width = 900;
            var top =  (screen.height/2)-(height/2) - 100;
            var left = (screen.width/2)-(width/2);
            var win = window.open('/lfm?type='+$(this).attr('href'), '', 'width='+width+',height='+height+',top='+top+',left='+left);

            var timer = setInterval(function() {
                if(win.closed) {
                    clearInterval(timer);
                    location.reload();
                }
            }, 500);
        });
    </script>
@endpush
