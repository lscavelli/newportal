<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\EmailConfirmation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    private $token;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     * RegisterController constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        if (!array_get(cache('settings'), 'open_registration')) {
            app()->abort(404, 'Pagina non trovata');
        }
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nome' => 'required|string|min:3|max:255',
            'cognome' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * @param array $data
     * @return mixed
     */
    protected function create(array $data)
    {
        $this->token = $this->makeToken();
        return User::create([
            'nome' => $data['nome'],
            'cognome' => $data['cognome'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'confirmation_token' => $this->token,
            'status_id' => 3
        ]);
    }

    /**
     * crea e restituisce il token per la conferma della registrazione
     * @return string
     */
    protected function makeToken() {
        return hash_hmac('sha256', str_random(60), config('app.key'));
    }

    /**
     * Verifica il token in argomento e autentica l'utente registrato
     * @param $token
     * @return mixed
     */
    protected function confirmationEmail($token) {
        $user = User::TokenVerification($token)->firstOrFail();
        $user->email_verified_at = now();
        $user->confirmation_token = null;
        $user->status_id = 1;
        $user->save();
        $this->guard()->login($user);
        return redirect('admin/users/'.$user->id)->withSuccess('L\'Email è stata confermata correttamente');
    }

    /**
     * Override di register - si evita il login automatico dopo la registrazione
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
	public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        //$this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    public function registered(Request $request, $user)
    {
        $user->notify(new EmailConfirmation($this->token));
        redirect()->back()->withSuccess('Ti è stata inviata una email. Per completare la registrazione clicca sul pulsante "Conferma email" che trovi nel messaggio che hai ricevuto. Se non trovi alcun messaggio, controlla nella posta indesiderata, nel cestino, negli elementi eliminati o nell\'archivio.' );
    }
}
