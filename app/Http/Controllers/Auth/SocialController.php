<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Socialite;
use Laravel\Socialite\Contracts\User as SocialUser;

class SocialController extends Controller
{

    private $rp;

    public function __construct(RepositoryInterface $rp)
    {
        $this->middleware('guest');
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

        if (is_null($user)) {
            if (!array_get(cache('settings'), 'social_registration')) {
                return redirect('login')->withErrors("Accesso riservato agli utenti già registrati");
            }
            $user = $this->createSocialUser($socialUser,$provider);
        }

        Auth::login($user);
        return redirect()->route('dashboard');
    }

    private function createSocialUser(SocialUser $socialUser,$provider)
    {
        $name = explode(" ", $socialUser->getName());

        $user = $this->rp->create([
            'email' => $socialUser->getEmail(),
            'password' => bcrypt('password'),
            'cognome' => array_pop($name),
            'nome' => implode(' ',$name),
            'status_id' => 1,
            'avatar' => $socialUser->getAvatar()
        ]);

        $this->rp->setModel('App\Models\Social_auth');

        $this->rp->create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
            'avatar' => $socialUser->getAvatar()
        ]);

        //Assegnare un ruolo di site mamber

        return $user;
    }
}
