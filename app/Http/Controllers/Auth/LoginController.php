<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Session;
use App\Company;
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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // protected function authenticated(Request $request, $user)
    // {
    //     $this->setUserSession($user);
    // }
   
    // protected function setUserSession($user)
    // {
    //     if($user->hasRole(['Super Admin','Admin'])){
    //         $company = $user->company;
    //         if($company->company_logo==NULL|$company->company_logo==''){
    //             Session::put('logo','images/lemon.png');
    //         }
    //         else{
    //             Session::put('logo','storage/images/logos/'.$company->company_logo);
    //         }
    //         Session::put('theme',$company->theme);
    //     }
    //     else{ 
    //         if($user->creators[0]->hasRole('Admin')){
    //             $company = Company::where('user_id', $user->creators[0]->id)->first();
    //             Session::put('theme', $company->theme);
    //             Session::put('logo','storage/images/logos/'.$company->company_logo);

    //         }
    //         else{
    //             $regionalAdmin = $user->creators[0];
    //             $company = Company::where('user_id',$regionalAdmin->creators[0]->id)->first();
    //             Session::put('theme', $company->theme);
    //             Session::put('logo','storage/images/logos/'.$company->company_logo);

    //         }
    //         Session::put('theme', 'themePurple');
    //     }
    // }
}
