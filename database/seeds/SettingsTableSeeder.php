<?php

use Illuminate\Database\Seeder;
use App\Models\General\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cache::forget('settings');
        $settings = Setting::pluck('setting_value', 'setting_key')->all();
        Cache::forever('settings', $settings);

    }
}
