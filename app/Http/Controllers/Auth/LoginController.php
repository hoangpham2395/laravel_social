<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    public function index()
    {
        return view('login.index');
    }

    public function yahooRedirect()
    {
        return redirect(getConstant('YAHOO_API_REQUEST_AUTH'));
    }

    public function yahooCallback(Request $request)
    {
        $data = $request->all();

        $url = getConstant('YAHOO_API_GET_TOKEN');
        $option = [
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode(getConstant('YAHOO_CLIENT_ID') . getConstant('YAHOO_CLIENT_SECRET')),
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'client_id' => getConstant('YAHOO_CLIENT_ID'),
                'client_secret' => getConstant('YAHOO_CLIENT_SECRET'),
                'redirect_uri' => getConstant('YAHOO_URI_CALLBACK'),
                'grant_type' => 'authorization_code',
                'code' => array_get($data, 'code'),
            ],
        ];

        dd($this->callApi($url, $option));
    }

    public function callApi($url, $option = [], $method = "POST")
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request($method, $url, $option);

            return $response->getBody()->getContents();
        } catch (ClientException $e) {
            return $e->getMessage();
        }
    }
}
