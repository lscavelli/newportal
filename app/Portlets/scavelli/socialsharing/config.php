<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Portlets Social Sharing
    |--------------------------------------------------------------------------
    | condivisione dei contenuti sui social
    |
    */
    'portlets' => [
        'viewWebContent' => [
            'name'          =>  'Condivisione contenuti sui social',
            'author'        =>  'Scavelli',
            'revision'      =>  '1.0',
            'date'          =>  '30/10/2017',
            'description'   =>  '',
            'type'          =>  '1',
            'path'          =>  'scavelli\socialsharing'
        ]
    ],
    'providers' => [
        'facebook' => [
            'uri' => 'https://www.facebook.com/sharer/sharer.php?u=',
        ],
        'twitter' => [
            'uri' => 'https://twitter.com/intent/tweet?url=',
            'param'=>['original_referer','text'],
        ]
    ],



];