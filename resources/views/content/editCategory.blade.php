{{--dd($users->toArray())--}}

@extends('layouts.admin')

@section('breadcrumb')
    {!! $breadcrumb->add('Categorie','/admin/vocabularies/cat/'.$vocabulary->id)->add('Vocabolari','/admin/vocabularies')->add('Aggiorna categorie')
        ->setTcrumb('Vocabolario: '.$vocabulary->name)
        ->render() !!}
@stop


@section('content')
    @include('ui.messages')
    <div class="row">
        <!-- col -->
        <div class="col-md-{{ ($category->id) ? 9 : 12 }}">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">Dati obbligatori</a></li>
                    @isset($category->id)<li><a href="#tags" data-toggle="tab">Tags</a></li>@endisset
                </ul>
                <div class="tab-content">
                    <!-- /.tab-pane -->
                    <div class="tab-pane active" id="settings">

                        {!! Form::model($category, ['action' => $action,'class' => 'form-horizontal']) !!}
                            {!! Form::hidden('vocabulary_id',$vocabulary->id) !!}

                            {!! Form::slText('vocabulary_name','Vocabolario',$vocabulary->name,['class' => 'form-control', 'disabled'=>'']) !!}
                            {!! Form::slText('name','Nome') !!}
                            {!! Form::slText('code','Codice') !!}
                            {!! Form::slColorPicker('color','Colore',$category->id) !!}
                            {!! Form::slSelect('parent_id','Sottocategoria di',$selectCat) !!}
                            {!! Form::slCkeditor('description','Descrizione') !!}
                            {!! Form::slSubmit('Salva') !!}

                        {!! Form::close() !!}
                    </div>
                    <!-- /.tab-pane input-sm-->
                    @isset($category->id)
                        <!-- tab-pane -->
                        <div class="tab-pane" id="tags">

                            {!! Form::model($category, ['url' => url('admin/vocabularies/cat/update',$category->id),'class' => 'form-horizontal']) !!}
                                {!! Form::hidden('vocabulary_id',$vocabulary->id) !!}
                                {!! Form::slText('name','Titolo',null,['disabled'=>'']) !!}
                                {!! Form::slTags($tags,$category) !!}
                                {!! Form::slSubmit('Salva',['name'=>'saveTags']) !!}
                            {!! Form::close() !!}

                        </div>
                        <!-- /.tab-pane -->
                    @endisset
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
        @if($category->id)
        <!-- col -->
        <div class="col-md-3">
            <div class="box box-solid">
                <div class="box-header with-border" style="background-color: #f8f8f8; border-radius: 3px">
                    <h3 class="box-title">Immagini categoria</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding" style="display: block;">



                    <div class="image" style="padding: 10px">
                        <div class="row">
                            <div class="col-2" style="padding-left: 25px">
                                <a href="{{ url('admin/files/create') }}" class="btn btn-default btn-xs">Nuovo file</a>
                            </div>
                            <div class="col-10"></div>
                        </div>
                        <div class="row">
                            <div class="col-12">

                                {!!
                                   $listFiles->columns(['thumb'=>__('Anteprima')])
                                   ->showSearch(false)->showActions(true)->showButtonNew(false)->showXpage(false)
                                   ->sortFields(['id'])
                                   ->actions(function($row) {
                                       return '
                                       <li><a href="'.url('/admin/files/'.$row['id'].'/edit').'">Edita</a></li>
                                       <li><a href="#" class="delete" data-id="'.$row['id'].'">Delete</a></li>';
                                   },false)
                                   ->setUrlDelete('/admin/files')
                                   ->customizes('thumb',function($row){
                                       $file = "/".config('lfm.thumb_folder_name')."/".$row['file_name'];
                                       $pathFile = $row->getPath().$file;
                                       if($row->isImage() && file_exists($pathFile)) {
                                           return '<div style="text-align:center"><img src=\''.asset("storage/".$row['path'].$file).'\' alt=\''.$row['name'].'\' style="width: 100%; max-width: 45px; height: auto; border-radius: 50%;"></div>';
                                       } else {
                                           return '<div style="text-align:center"><i class="fa '.$row->getIcon() .' fa-3x"></i></div>';
                                       }
                                   })->render()
                               !!}
                            </div>
                        </div>


                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
        <!-- /.col -->
        @endif
    </div>
    <!-- /.row -->
@stop

