<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Portlets Contenuto web
    |--------------------------------------------------------------------------
    |  i frame del master sono sempre disponibili per l'assegnazione dei contenuti
    |
    */
    'portlets' => [
        'viewWebContent' => [
            'name'          =>  'Visualizzazione Contenuto Web',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '19/02/2017',
            'description'   =>  '',
            'type'          =>  '1',
            'path'          =>  'scavelli\webcontent'
        ]
    ],
    'providers' => [
        'facebook' => [
            'uri' => 'https://www.facebook.com/sharer/sharer.php?u=',
            'icon' => 'fa-facebook'
        ],
        'twitter' => [
            'uri' => 'https://twitter.com/intent/tweet?url=',
            'param'=>['original_referer','text'],
            'icon' => 'fa-twitter'
        ]
    ],

];