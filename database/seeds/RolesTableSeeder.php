<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'Super Administrator',
            'slug' => config('newportal.super_admin'),
            'description' => 'Questo ruolo consente l\'accesso a tutte le funzionalità del sistema senza avere alcun permesso specifico',
            'level' => 5
        ]);

        Role::create([
            'name' => 'Content manager',
            'slug' => 'manager-content',
            'description' => 'Consente la gestione dei contenuti compreso strutture, modelli e data set'
        ]);

        Role::create([
            'name' => 'User manager',
            'slug' => 'users-manager',
            'description' => 'Consente la gestione degli utenti compreso l\'assegnazione dei ruoli e permessi. 
                    Il ruolo "Super Admin" accede comunque a tutte le funzionalità di sistema'
        ]);
    }
}
