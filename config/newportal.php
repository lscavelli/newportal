<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stato utente
    |--------------------------------------------------------------------------
    |
    | Stato dell'utente
    |
    |
    */

    'status_user' => [
        1   =>  'Attivo',
        2   =>  'Disattivo',
        3   =>  'Da confermare',
        4   =>  'Bandito'
    ],

    'path_upload_user' => 'img/upload/users',

    /*
    |--------------------------------------------------------------------------
    | Livello ruolo
    |--------------------------------------------------------------------------
    |
    */

    'levelRole' => [
        1   =>  1,
        2   =>  2,
        3   =>  3,
        4   =>  4,
        5   =>  5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Ruolo di super admin
    |--------------------------------------------------------------------------
    |
    | questo ruolo consente l'accesso a tutte le funzionalità e i componenti del
    | sistema
    */

    'super_admin' => 'super_admin',

    /*
    |--------------------------------------------------------------------------
    | Stato di uso frequente nelle tabelle
    |--------------------------------------------------------------------------
    |
    | Stato generale
    |
    |
    */

    'status_general' => [
        1   =>  'Attivo',
        0   =>  'Disattivo'
    ],

    /*
    |--------------------------------------------------------------------------
    | Organization
    |--------------------------------------------------------------------------
    |
    | Tipologia organizzazioni
    |
    */

    'type_organization' => [
        1   =>  'Regolare',
        2   =>  'Sede'
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme e Layout
    |--------------------------------------------------------------------------
    |
    | Definisce il tema e il layout di default
    |
    */

    'theme-default' => 'default',
    'layout-default' => 'default',
    'partial-default' => 'trasparent',
    'theme-dir' => 'themes',
    'themeSubDir' => array(
        'view'    => 'views',
        'layout'  => 'layouts',
        'asset'   => 'assets',
        'partial' => 'partials',
    ),

    /*
    |--------------------------------------------------------------------------
    | Widgets
    |--------------------------------------------------------------------------
    |
    | Impostazioni per i widgets
    |
    */

    'widgets' => [
        'namespace' => 'Widgets',
    ],

    /*
    |--------------------------------------------------------------------------
    | Vocabolari
    |--------------------------------------------------------------------------
    |
    | definizione dei servizi interni che richiedono l'uso di uno o più vocabolari
    |
    */
    'services' => [
        'App\Models\Content\Content' => 'ContentWeb',
        'App\Models\Blog\Post' => 'Blog',
        'App\Models\Content\File' => 'Documenti',
    ],

    /*
    |--------------------------------------------------------------------------
    | WebContent
    |--------------------------------------------------------------------------
    |
    | dir per l'upload delle immagini dei web content - sistemazione temporanea
    |
    */
    'path_images_wc' => 'img/webcontent',


    /*
    |--------------------------------------------------------------------------
    | Page
    |--------------------------------------------------------------------------
    |
    | Tipo della pagina
    |
    |
    */

    'type_page' => [
        0   =>  'Widget',
        1   =>  'Url',
        2   =>  'Pagina interna'
    ],

    /*
    |--------------------------------------------------------------------------
    | Disk predefinito
    |--------------------------------------------------------------------------
    |
    | Utilizzato nel model file
    |
    |
    */

    'disk' => 'public'

];
