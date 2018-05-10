<?php

return [
    /*
    |--------------------------------------------------------------------------
    | portlets Image Viewer
    |--------------------------------------------------------------------------
    |  Visualizzatore di immagini con possibilità di mostrare
    |  le immagini categorizzate
    |
    */
    'portlets' => [
        'imageViewer' => [
            'name'          =>  'Visualizzatore di immagini',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '30/04/2018',
            'description'   =>  'Consente di visualizzare le immagini singolarmente o in modalità multipla',
            'type'          =>  '1',
            'path'          =>  'scavelli\imageviewer',
            'service'       =>  'App\Models\Content\File'
        ]
    ]
];