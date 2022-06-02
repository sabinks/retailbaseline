<?php

namespace App\Http\Requests\API\V1;

use Illuminate\Foundation\Http\FormRequest as Request;
use Illuminate\Support\Facades\Auth;

class EntityFormRequest extends Request
{
    protected $rules=[];
    protected $messages=[];
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        if ($user->hasRole('Super Admin')) {
            $this->rules = [
                'form_title' => 'required',
                'client_id' => 'required',
                'inputs' => 'json'
            ];
            $this->messages = [
                'form_title.required'  => 'Form title is required',
                'client_id.required'  => 'You must select a client company',
            ];
        }else if($user->hasRole('Admin') || $user->hasRole('Regional Admin')){
            $this->rules = [
                'form_title' => 'required',
                'inputs' => 'json'
            ];
            $this->messages = [
                'form_title.required'  => 'Form title is required',
            ];
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return $this->messages;
    }
}
