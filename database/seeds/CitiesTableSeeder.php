<?php

use Illuminate\Database\Seeder;
use App\Models\Data\City;

class CitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cities')->delete();
        $json = File::get(base_path('database/data/comuni.json'));
        $data = json_decode($json,true);
        foreach ($data as $obj) {
            City::create(array(
                'code' => $obj['codice'],
                'name' => $obj['nome'],
                'regionCode' => $obj['regione']['codice'],
                'region' => $obj['regione']['nome'],
                'provinceCode' => (is_numeric($obj['provincia']['codice']) ? $obj['provincia']['codice'] : 0 ),
                'province' => $obj['provincia']['nome'],
                'cmCode' => (is_numeric($obj['cm']['codice']) ? $obj['cm']['codice'] : 0) ,
                'cm' => $obj['cm']['nome'],
                'initials' => $obj['sigla'],
                'cadastralCode' => $obj['codiceCatastale'],
                'cap' => (!is_array($obj['cap']) ? $obj['cap']:$obj['cap'][0]),
            ));
        }
    }
}
