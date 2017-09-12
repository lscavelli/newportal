<?php
/**
 * Created by PhpStorm.
 * User: prefbat02
 * Date: 25/10/2016
 * Time: 16:58
 */

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
    'partial-default' => 'default',
    'theme-dir' => 'themes',
    'themeSubDir' => array(
        'view'    => 'views',
        'layout'  => 'layouts',
        'asset'   => 'assets',
        'partial' => 'partials',
    ),

    /*
    |--------------------------------------------------------------------------
    | Portlet
    |--------------------------------------------------------------------------
    |
    | Impostazioni per le portlet
    |
    */

    'portlets' => [
        'namespace' => 'Portlets',
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
    ],

    /*
    |--------------------------------------------------------------------------
    | WebContent
    |--------------------------------------------------------------------------
    |
    | dir per l'upload delle immagini - sistemazione temporanea
    |
    */
    'path_upload_imgwc' => 'img/webcontent',
];