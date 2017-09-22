<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\FacebookApp;
use Facebook\FacebookRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


use League\Flysystem\Config;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->scopes(['user_friends'])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('facebook')->user();


        $fb = new Facebook([
            'app_id' => '2044135929139630',
            'app_secret' => '09ebebbf36a8cd2b14e76ba450cdaccc',
            'default_graph_version' => 'v2.10',
        ]);

        try {
            // Requires the "read_stream" permission
            $response = $fb->get("/{$user->id}/taggable_friends?fields=name,picture.width(720).height(720),limit=200", $user->token);
        } catch(FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $nextFeed = $response->getGraphEdge();

        foreach ($nextFeed as $status) {
            var_dump($status->asArray());
        }

        while ($nextFeed = $fb->next($nextFeed)) {
            foreach ($nextFeed as $status) {
                var_dump($status->asArray());
            }
        }


        dd($user);
    }

}
