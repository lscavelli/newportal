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
    }
}
