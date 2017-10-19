<?php

namespace App\Http\Controllers\General;

use Illuminate\Http\Request;
use App\Repositories\RepositoryInterface;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\Cache;


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
            'open_registration' => 'required|boolean'
        ]);
    }

    /**
     * Mostra il form con i settings
     * @return \Illuminate\Contracts\View\View
     */
    public function index()   {
        $settings = $this->rp->all()->toArray(); $action = "General\\SettingController@storeOrUpdate";
        foreach($settings as $s) {
            $this->settings[$s['setting_key']] = $s['setting_value'];
        }
        return view('general.setting')->with([
            'settings' => $this,
            'action' => $action
        ]);
    }

    public function get($key) {
        if (!empty($key) and key_exists($key,$this->settings)) {
            return $this->settings[$key];
        }
    }

    /**
     * Switch Insert Or Update
     * @param Request $request
     */
    public function storeOrUpdate(Request $request)   {
        $settings = $request->except('_token');
        $this->validator($settings)->validate();
        $data = Collect($this->rp->all()->toArray());
        foreach($settings as $key=>$value) {
            if ($data->contains('setting_key',$key)) {
                $this->update($key,$value);
            } else {
                $this->insert($key,$value);
            }
            Cache::forever($key, $value);
        }
        return redirect()->route('settings')->withSuccess('Pagina creata correttamente.');
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
