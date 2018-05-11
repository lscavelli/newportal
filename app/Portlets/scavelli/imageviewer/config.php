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
            'date'          =>  '07/05/2018',
            'description'   =>  'Widget per la visualizzare delle immagini singole e multiple',
            'type'          =>  '1',
            'path'          =>  'scavelli\imageviewer',
            'service'       =>  'App\Models\Content\File'
        ]
    ]
];