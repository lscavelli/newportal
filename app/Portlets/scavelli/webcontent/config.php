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
            'revision'      =>  '1.2',
            'date'          =>  '07/04/2018',
            'description'   =>  'Widget per la visualizzazione dei contenuti web - completo di strutture e modelli',
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
            'text'=>'Nuovo articolo',
            'original_referer'=>'',
            'icon' => 'fa-twitter'
        ]
    ],

];