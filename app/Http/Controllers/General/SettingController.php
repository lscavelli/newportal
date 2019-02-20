<?php
/**
 * for controll
 * es. if (!array_get(cache('settings'), 'social_registration')) {
 *
 */

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Cache;
use App\Models\Content\Page;


class SettingController extends Controller {

    private $rp;
    public $settings;

    public function __construct(RepositoryInterface $rp)  {
        $this->middleware('auth');
        $this->rp = $rp->setModel('App\Models\General\Setting');
        $this->settings = [];
    }

    /**
     * @param array $data
     * @return mixed
     */
    private function validator(array $data)   {
        return Validator::make($data, [
            //'open_registration' => 'required|boolean'
        ]);
    }

    /**
     * Mostra il form con i settings
     * @return \Illuminate\Contracts\View\View
     */
    public function index()   {
        $action = "General\\SettingController@storeOrUpdate";
        $this->settings =  $this->rp->pluck('setting_value', 'setting_key')->all();
        $pages = $this->rp->setModel(Page::class)->optionsSel(null,null,'name','slug');
        return view('general.setting')->with([
            'settings' => $this,
            'action' => $action,
            'pages' => $pages
        ]);
    }

    /**
     * restituisce la key passata come parametro - richiamata dalla view
     * @param $key
     * @return mixed
     */
    public function get($key) {
        if (!empty($key) and key_exists($key,$this->settings)) {
            return $this->settings[$key];
        }
    }

    /**
     * Switch Insert Or Update
     * @param Request $request
     * @return mixed
     */
    public function storeOrUpdate(Request $request)   {
        $settings = $request->except('_token');
        $this->validator($settings)->validate();
        $data = $this->rp->pluck('setting_value', 'setting_key')->all();
        foreach($settings as $key=>$value) {
            if (array_key_exists($key,$data)) {
                $this->update($key,$value);
            } else {
                $this->insert($key,$value);
            }
        }
        $updateSettings = $this->rp->pluck('setting_value', 'setting_key')->all();
        Cache::forever('settings', $updateSettings);
        return redirect()->back()->withSuccess('Impostazioni registrate correttamente.');
    }

    /**
     * inserisce una nuova key
     * @param $key
     * @param $value
     */
    public function insert($key,$value)   {
        if (!empty($key)) {
            $data['setting_key'] = $key;
            $data['setting_value'] = $value;
            $this->rp->create($data);
        }
    }

    /**
     * aggiorna la key
     * @param $key
     * @param $value
     */
    public function update($key,$value)   {
        $setting = $this->rp->findBy(['setting_key'=>$key]);
        if ($setting)
            $this->rp->update($setting->id,['setting_value'=>$value]);
    }



}
