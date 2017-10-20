<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Socialite;

class SocialController extends Controller
{

    private $rp;

    public function __construct(RepositoryInterface $rp)
    {
        $this->rp = $rp->setModel('App\Models\User');
    }

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param $provider
     * @return $this|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function getProviderCallback($provider)
    {
        $socialUser = Socialite::with($provider)->user();
        $user = $this->rp->findByEmail($socialUser->getEmail());

        if (!is_null($user)) {
            Auth::login($user);
            return redirect()->route('dashboard');
        } elseif (array_get(cache('settings'), 'social_registration')===1) {
            // TODO crea l'user con i dati di $socialUser
        }
        return redirect('login')->withErrors("Accesso riservato agli utenti già registrati");
    }
}
