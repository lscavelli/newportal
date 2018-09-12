{!!
    $navigation->add([
        'Contenuto'=>['icon'=>'fa-file-text-o','url'=>url('/admin/content/'.$content->id.'/edit')],
        'Estratto'=>['icon'=>'fa-bars','url'=>url('/admin/content/estratto',$content->id)],
        'Categorizzazione'=>['icon'=>'fa-tags','url'=>url('/admin/content/categorization',$content->id)],
        'Schedulazione'=>['icon'=>'fa-calendar','url'=>'#'],
        'Elementi correlati'=>['icon'=>'fa-share-alt','url'=>'#'],
        'Modello'=>['icon'=>'fa-file-code-o','url'=>url('/admin/content/model',$content->id)],
    ])->render("ui.navigation_content")
!!}