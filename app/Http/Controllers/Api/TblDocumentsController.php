<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblDocuments;
use App\TblGroups;
use App\TblDocumentTypes;
use Carbon\Carbon;

class TblDocumentsController extends Controller
{
    public function documents($id){

        $documentType = TblDocumentTypes::where('doc_types_id', $id)->select('group_id')->first();
        $work_times = TblGroups::where('group_id', $documentType->group_id)->select(  'ticket_time_from',
                                                                                      'ticket_time_to',
                                                                                      'work_break_from',
                                                                                      'work_break_to')->first();

        $start_work_time  = Carbon::parse($work_times->ticket_time_from)->format('H:i');
        $end_work_time    = Carbon::parse($work_times->ticket_time_to)->format('H:i');
        $start_break_time = Carbon::parse($work_times->work_break_from)->format('H:i');
        $end_break_time   = Carbon::parse($work_times->work_break_to)->format('H:i');
        $now   = Carbon::now('Asia/Ashgabat')->format('H:i');

        if ($now > $start_work_time && $now < $start_break_time ||  $now > $end_break_time && $now < $end_work_time) {

            $data = TblDocuments::where('doc_types_id', $id)->orderBy('doc_order', 'asc')
                                                            ->select( 'doc_id',
                                                                      'doc_name_tm',
                                                                      'doc_name_ru')->get();
            return response()->json(['data'=>$data]);
        }
            return response()->json(['message'=>'time_is_up']);
    }
}
