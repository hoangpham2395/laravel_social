<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Base\BaseController;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class LoginController
 * @package App\Http\Controllers\Frontend\Auth
 */
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

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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


    /**
     * Login by socialite (facebook, google, twitter, github)
     * @param $social
     * @return mixed
     */
    public function redirect($social)
    {
        try {
            return Socialite::driver($social)->redirect();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * @param $social
     */
    public function callback($social)
    {
        try {
            $params = Input::all();
            // Error
            if (!empty($params['error_code'])) {
                dd($params);
            }

            // Success
            $user = Socialite::driver($social)->user();

            $r = [
                'profile_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'image' => $user->avatar,
                'access_token' => $user->token,
            ];

            dd($r);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    /**
     * Login by yahoo
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function yahooRedirect()
    {
        return redirect(getConstant('YAHOO_API_REQUEST_AUTH'));
    }

    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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
                'code' => data_get($data, 'code'),
            ],
        ];

        $tokens = $this->callApi($url, $option);
        if (empty(data_get($tokens, 'xoauth_yahoo_guid'))) {
            dd('Login yahoo is failed. Did not get token, try again with other code!');
        }

        // Call api get token exchange (Khi access_token thì dùng refesh_token để tạo access_token mới)
//        $optionExchange = [
//            'headers' => [
//                'Authorization' => $authorization,
//                'Content-Type' => 'application/x-www-form-urlencoded',
//            ],
//            'form_params' => [
//                'client_id' => $clientId,
//                'client_secret' => $clientSecret,
//                'redirect_uri' => $returnUri,
//                'grant_type' => 'refresh_token',
//                'refresh_token' => $tokens->refresh_token,
//            ],
//        ];
//
//        $tokensExchange = $this->callApi($url, $optionExchange);

        // Call api get profile
        $urlProfile = "https://social.yahooapis.com/v1/user/". $tokens->xoauth_yahoo_guid ."/profile?format=json";
        $optionProfile = [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokens->access_token,
            ],
        ];

        $profile = $this->callApi($urlProfile, $optionProfile, "GET");
        // Error
        if (empty($profile->profile)) {
            dd($profile);
        }

        // Get profile
        $r = [
            'profile_id' => data_get($profile, 'profile.guid'),
            'name' => data_get($profile, 'profile.givenName') . ' ' . data_get($profile, 'profile.familyName'),
            'email' => data_get($profile, 'profile.emails.0.handle'),
            'phone' => data_get($profile, 'profile.phones.0.number'),
            'image' => data_get($profile, 'profile.image.imageUrl'),
            'access_token' => data_get($tokens, 'access_token'),
        ];

        dd($r);
    }

    /**
     * Login by zalo
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function zaloRedirect()
    {
        return redirect(getConstant('ZALO_API_REQUEST_AUTH'));
    }

    /**
     * @param Request $request
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function zaloCallback(Request $request)
    {
        $data = $request->all();
        $profileId = data_get($data, 'uid');
        $code = data_get($data, 'code');

        $urlGetToken = 'https://oauth.zaloapp.com/v3/access_token?app_id='. getConstant('ZALO_CLIENT_ID') .'&app_secret='. getConstant('ZALO_CLIENT_SECRET') .'&code='. $code;
        $tokens = $this->callApi($urlGetToken, [], "GET");

        $urlGetProfile = 'https://graph.zalo.me/v2.0/me?access_token='. $tokens->access_token . '&fields=id,birthday,name,gender,picture';
        $profile = $this->callApi($urlGetProfile, [], 'GET');

        $data = [
            'id' => data_get($profile, 'id'),
            'name' => data_get($profile, 'name'),
            'gender' => data_get($profile, 'gender'),
            'birthday' => data_get($profile, 'birthday'),
            'image' => data_get($profile, 'picture.data.url'),
        ];

        dd($data);
    }

    public function lineRedirect()
    {

    }

    public function lineCallback(Request $request)
    {

    }

    /**
     * @param $url
     * @param array $option
     * @param string $method
     * @return mixed|string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
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
