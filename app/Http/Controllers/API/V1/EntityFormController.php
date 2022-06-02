<?php

namespace App\Http\Controllers\API\V1;

use Auth;
use App\EntitiesForm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EntityFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $user = Auth::user();
        $forms = $user->assignedEntitiesForms()
            ->with(['formCreator', 'clients:company_name,company_phone_number'])
            ->get()->toArray();
        
        foreach ($forms as $formIndex=>$form) {
        $forms[$formIndex]['inputs'] = json_decode($forms[$formIndex]['inputs'], true );
            foreach ($forms[$formIndex]['inputs'] as $index=>$input ) {
                if ($input['element'] == 'Header'||$input['element'] == 'TextInput'||$input['element'] == 'TextArea'||$input['element'] == 'NumberInput'||$input['element'] == 'Camera'||$input['element'] == 'DatePicker') {
                    if ($input['element'] == 'Header'){
                        $forms[$formIndex]['inputs'][$index]["label"]=$forms[$formIndex]['inputs'][$index]["content"];
                        $forms[$formIndex]['inputs'][$index]["field_name"]=null; 
                    }
                    $forms[$formIndex]['inputs'][$index]["options"]=[
                        [
                            "key"=>null,
                            "text"=>null,
                            "value"=>null
                        ]];
                }
            }
        }
        return response()->json(['data' => $forms], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function show($formId)
    {
        $form = Auth::user()->assignedEntitiesForms()->with('formCreator')->findOrFail($formId);
        $form = json_decode($form, true);
        $form['inputs']= json_decode($form['inputs'], true);
        foreach ($form['inputs'] as $index=>$input ) {
            if ($input['element'] == 'Header'||$input['element'] == 'TextInput'||$input['element'] == 'TextArea'||$input['element'] == 'NumberInput'||$input['element'] == 'Camera'||$input['element'] == 'DatePicker') {
                if ($input['element'] == 'Header'){
                    $form['inputs'][$index]["label"]=$form['inputs'][$index]["content"];
                    $form['inputs'][$index]["field_name"]=null; 
                }
                $form['inputs'][$index]["options"]=[
                    [
                        "key"=>null,
                        "text"=>null,
                        "value"=>null
                    ]];
            }
        }
        
        return response()->json(['data'=>$form], 200);
    }

    public function edit(EntitiesForm $form)
    {
        //
    }

    public function update(Request $request, $form)
    {
        dd($request->all());
    }

    public function destroy(EntitiesForm $form)
    {
        //
    }
}
