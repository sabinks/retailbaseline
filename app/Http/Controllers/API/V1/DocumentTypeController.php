<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stock\DocumentType;

class DocumentTypeController extends Controller
{
    public function index(){
        $document_list = DocumentType::get(['id', 'name']);
        
        return response()->json([
            'document_list' => $document_list
        ], 200);
    }
}
