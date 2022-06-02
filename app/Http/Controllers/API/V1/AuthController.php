<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Route;
use Auth;
use DB;
use Cookie;

class AuthController extends Controller
{
    private $client;

    public function __construct(){
    	 $this->client = Client::where('password_client', true)
         ->first();
    }

    public function login(Request $request){
    	$request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $success = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $request->email,
            'email' => $request->email,
            'password' => $request->password,
            'scope' => '*'
        ];
        $request->request->add($success);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }

    public function refreshToken(Request $request){
        
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        $success = [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => '*'
        ];

        $request->request->add($success);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy); 
    }

    public function logout(Request $request){
    	
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(['revoked' => true]);

        $accessToken->revoke();

        return response()->json([ 'message' => 'Successfully Logged Out'], 204);
    }  
}
