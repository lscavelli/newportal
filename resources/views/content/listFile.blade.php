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
                    $list->columns(['id'=>'Id','thumb'=>__('Anteprima'),'name'=>'Titolo','status_id'=>__('Stato'),'created_at'=>__('Creato il')])
                    ->addSplitButtons([
                        'form' => 'Nuovo file',
                        'file'=>'Apri file manager',
                    ],false)
                    ->sortFields(['id','name','file_name'])
                    ->customizes('created_at',function($row){
                        return $row['created_at']->format('d/m/Y');
                    })
                    ->customizes('status_id',function($row){
                        return config('newportal.status_general')[$row['status_id']];
                    })
                    ->customizes('name',function($row){
                        return Html::link(url("/admin/files/view",$row['id']), $row['name'], array('title' => $row['name']), true);
                    })
                    ->customizes('thumb',function($row){
                        $file = "/".config('newportal.thumb_folder_name')."/".$row['file_name'];
                        $pathFile = $row->getPath().$file;
                        if($row->isImage() && file_exists($pathFile)) {
                            return '<div style="text-align:center"><img src=\''.asset("storage/".$row['path'].$file).'\' alt=\''.$row['name'].'\' style="width: 100%; max-width: 45px; height: auto; border-radius: 50%;"></div>';
                        } else {
                            return '<div style="text-align:center"><i class="fa '.$row->getIcon() .' fa-3x"></i></div>';
                        }
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
            if($(this).attr('href')==='form') {
                location.href = '/admin/files/create';
                return false;
            };
            e.preventDefault();

            let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            let y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;
            let width = x * 0.8;
            let height = y * 0.8;
            var top =  (screen.height/2)-(height/2) - 100;
            var left = (screen.width/2)-(width/2);

            var win =  window.open('/file-manager/fm-button', 'fm', 'width='+width+',height='+height+',top='+top+',left='+left);

            var timer = setInterval(function() {
                if(win.closed) {
                    clearInterval(timer);
                    location.reload();
                }
            }, 500);
        });
    </script>
@endpush
