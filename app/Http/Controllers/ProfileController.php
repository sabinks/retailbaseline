<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    function __construct()
    {
        $this->middleware('role:Super Admin',['only'=>'viewReset','storeResetPassword']);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $profile)
    {
        return view('profile.index',[
            'user'=>$profile
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $profile)
    {
        if(Auth::user()->id==$profile->id){
            return view('profile.update',[
                'user'=>$profile
            ]);
        }
        else{
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $profile)
    {
        if(Auth::user()->id == $profile->id){
            $userInput = request()->validate([
                'name'=>'required',
                'phone_number'=>'required|unique:users,phone_number,'.$profile->id,
                'address'=>'required',
                'profile_image'=>'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
                'email'=>'required|email|unique:users,email,'.$profile->id
            ]);
            $profileImage = public_path("storage/images/profiles/{$profile->profile_image}");
            if($request->has('profile_image')){
                if(File::exists($profileImage))
                {
                    File::delete($profileImage);
                }
                $image = $request->file('profile_image');
                $destinationpath ='public/images/profiles';
                $imagename = Auth::user()->id.'_'.time(). '_'.$image->getClientOriginalName();
                $path = $image->storeAs($destinationpath, $imagename);
                $userInput['profile_image'] = $imagename;
            }
            $profile->update($userInput);
            return redirect('home')->with('message','Profile update Success');
        }
        else{
            abort(403);
        }
    }

    public function editPassword(){
        return view('profile.editPassword');
    }

    public function updatePassword(User $user,Request $request){
        $this->validCredentialData();
        $old_password = Auth::user()->password;
        if(Hash::check(request('old_password'),$old_password)){
            $password = bcrypt(request('new_password'));
            User::where('id',Auth::user()->id)->update([
                'password'=>$password
            ]);
            return redirect('/home')->with('message','Password Updated Successfully');
        }
        else{
            return redirect('/editPassword')->with('password_notice','Old password did not match');
        }
    }

    protected function validCredentialData(){
        return request()->validate([
            'old_password'=>'required',
            'new_password'=>'required|min:8',
            'confirm_password'=>'required|same:new_password'
        ]);
    }
    public function viewReset(){
        
    return view('profile.resetPassword');
        
    }
    public function storeResetPassword(Request $request,User $user){
        $this->validateInput();
        $user = User::where('email',request('email'))->first();
        if($user){
            if($user->hasRole(['Admin','Regional Admin','Supervisor','Field Staff'])){
                $new_password = bcrypt(request('new_password'));
                User::whereId($user->id)->update([
                    'password'=>$new_password
                ]);
                return redirect('/home')->with('message',' Password reset success');
            }
            else{
                return redirect('/resetPassword')->with('error','Please Change Your Password through setting');
            }
        }
        else{
            return redirect('/resetPassword')->with('error',"User not found!! Please enter valid user's email ");
        }
    }

    public function resetUserPassword($id){
        $user = User::findOrFail($id);
        $login_user = Auth::user();
        $return_back = '';
        $role = $user->getRoleNames()->first();
        if(($role == 'Admin' || $role == 'Supervisor') && ($login_user->hasRole(['Super Admin','Admin','Regional Admin']))){
            $return_back = Str::lower($role).'s';
        }
        else if(($role == 'Regional Admin') && ($login_user->hasRole('Admin'))) {
            $return_back = 'regionalAdmins';
        }
        else if(($role == 'Field Staff') && ($login_user->hasRole(['Admin','Regional Admin']))) {
            $return_back = 'mystaffs';
        }
        else if(($role == 'Field Staff') && ($login_user->hasRole('Super Admin'))){
            $return_back = 'staffs';
        }
        else{
            $return_back = 'home';
        }
        $creator = $user->creators()->first();
        if($creator->id == $login_user->id){
            return view('components.staff.resetPassword',compact('user','return_back'));
        }
        else{
            return redirect('/'.$return_back)->with('alert',$user->name.' is not created by you. You can not reset password');
        }
    }

    public function storeResetUserPassword(Request $request){
        $this->validateInput();
        $login_id = Auth::user()->id;
        $user = User::findOrFail(request('id'));
        $creator = $user->creators()->first();
        if($user->email == request('email')){
            if($creator->id == $login_id){
                User::whereId($user->id)->update([
                    'password'=>bcrypt(request('new_password'))
                ]);
                return redirect(request('redirect_to'))->with('message',' Password of '. $user->name .' reset successfully');
            }
            else{
                return redirect()->back()->with('error','This user is not created by you. You can not reset password');
            }
        }
        else{
            return redirect()->back()->with('error','Please do not modify email entered by system');
        }

    }

    public function validateInput(){
        $inputData = request()->validate([
            'email'=>'required|email',
            'new_password'=>'required|min:8',
            'confirm_password'=>'required|same:new_password'
        ]);
    }
}
