<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Company;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Session;
class CompanyController extends Controller
{
    public function myCompany(){
        $company = Auth::user()->companies[0];
        return view('profile.company',[
            'company'=>$company
        ]); 
    }

    public function editCompany(){
        if(Auth::user()->hasRole(['Super Admin','Admin'])){
            $company = Auth::user()->companies()->first();
            return view('profile.updateCompany',[
                'company'=>$company
            ]);
        }
    }

    public function updateCompany(Company $company,Request $request){
        $companyInput = request()->validate([
            'company_name'=>'required',
            'company_phone_number'=>'required|unique:users,phone_number,'.$company->id,
            'company_address'=>'required',
            'webaddress'=>'required|unique:companies,webaddress,'.$company->id
        ]);
        $companyLogo = public_path("storage/images/logos/{$company->company_logo}");
        if($request->has('company_logo')){
            if(File::exists($companyLogo))
            {
                File::delete($companyLogo);
            }
            $image = $request->file('company_logo');
            $destinationpath ='public/images/logos';
            $imagename = Auth::user()->id.'_'.time().'_'.$image->getClientOriginalName();
            $path = $image->storeAs($destinationpath, $imagename);
            $companyInput['company_logo'] = $imagename;
            Session::put('logo',$imagename);
        }
        if($company->update($companyInput)){
            return redirect('/home')->with('message','Company Info Updated');
        }
    }
}
