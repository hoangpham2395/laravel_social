<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Base\BaseController;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends BaseController
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

    public function getLogin()
    {
        return view('frontend.auth.login');
    }

    public function postLogin() 
    {

    }

    public function logout() 
    {

    }

    public function redirect($social) 
    {
        try {
            return Socialite::driver($social)->redirect();
        } catch (\Exception $e) {

        }
    }

    public function facebookCallback() 
    {
        try {
            $params = Input::all();
            // Error
            if (!empty($params['error_code'])) {
                dd($params);
            }

            // Success
            $user = Socialite::driver('facebook')->user();

            $r = [
                'profile_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'image' => $user->avatar_original,
                'access_token' => $user->token,
            ];

            dd($r);
        } catch (\Exception $e) {
            
        }
    }

    public function yahooRedirect()
    {
        return redirect(getConstant('YAHOO_API_REQUEST_AUTH'));
    }

    public function yahooCallback(Request $request)
    {
        $data = $request->all();

        // Yahoo developer
        $url = getConstant('YAHOO_API_GET_TOKEN');
        $clientId = getConstant('YAHOO_CLIENT_ID');
        $clientSecret = getConstant('YAHOO_CLIENT_SECRET');
        $returnUri = getConstant('YAHOO_URI_CALLBACK');
        $authorization = 'Basic ' . base64_encode($clientId . ":" . $clientSecret);

        // Call api get token
        $option = [
            'headers' => [
                'Authorization' => $authorization,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $returnUri,
                'grant_type' => 'authorization_code',
                'code' => array_get($data, 'code'),
            ],
        ];

        $tokens = $this->callApi($url, $option);

        // Call api get token exchange
        $optionExchange = [
            'headers' => [
                'Authorization' => $authorization,
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $returnUri,
                'grant_type' => 'refresh_token',
                'refresh_token' => $tokens->refresh_token,
            ],
        ];

        $tokensExchange = $this->callApi($url, $optionExchange);

        // Call api get profile
        $urlProfile = "https://social.yahooapis.com/v1/user/". $tokensExchange->xoauth_yahoo_guid ."/profile?format=json";
        $optionProfile = [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokensExchange->access_token,
            ],
        ];

        $profile = $this->callApi($urlProfile, $optionProfile, "GET");
        // Error
        if (empty($profile->profile)) {
            dd($profile);
        }

        // Get profile
        $r = [
            'profile_id' => $profile->profile->guid,
            'name' => $profile->profile->givenName . ' ' . $profile->profile->familyName,
            'email' => $profile->profile->emails[0]->handle,
            'phone' => $profile->profile->phones[0]->number,
            'country_code' => $profile->profile->intl,
            'image' => $profile->profile->image->imageUrl,
            'access_token' => $tokensExchange->access_token,
        ];

        dd($r);
    }

    public function callApi($url, $option = [], $method = "POST")
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request($method, $url, $option);

            return json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            return $e->getMessage();
        }
    }
}
