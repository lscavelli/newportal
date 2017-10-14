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
        return Socialite::with($provider)->redirect();
    }

    public function getProviderCallback($provider)
    {
        $socialUser = Socialite::with($provider)->user();
        $user = $this->rp->findByEmail($socialUser->getEmail());

        if (!is_null($user)) {
            Auth::login($user);
        }
        return redirect('/')->with('success', 'Successfully logged in!');
    }
}
