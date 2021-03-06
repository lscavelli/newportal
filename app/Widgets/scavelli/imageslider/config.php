<?php

return [
    /*
    |--------------------------------------------------------------------------
    | widget Image Slider
    |--------------------------------------------------------------------------
    |  Image Slider con possibilità di mostrare le immagini categorizzate
    |
    |
    */
    'widgets' => [
        'imageSlider' => [
            'id'            =>  3,
            'name'          =>  'Slider di immagini',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '07/05/2018',
            'description'   =>  'Widget per lo scorrimento delle immagini - Carousel',
            'type'          =>  '1',
            'path'          =>  'scavelli\imageslider',
            'service'       =>  'App\Models\Content\File'
        ]
    ]
];
