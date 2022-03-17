<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblDocumentTypes;

class TblDocumentTypesController extends Controller
{
    public function notoriat_actions(){

        $data = TblDocumentTypes::orderBy('doc_types_order', 'asc')->select('doc_types_id',
                                                                    'doc_types_name_tm',
                                                                    'doc_types_name_ru',
                                                                    'group_id')->get();
        return response()->json(['data'=>$data]);
    }
}
