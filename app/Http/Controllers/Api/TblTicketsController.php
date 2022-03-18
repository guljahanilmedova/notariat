<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\TblTickets;
use Carbon\Carbon;
use App\TblGroups;
use Illuminate\Http\Request;

class TblTicketsController extends Controller
{
    //store ticket start
    public function store_ticket(Request $request){
        
        $data = $request->all();
        $ticketcount = count(TblTickets::where('ticket_mobile', '+993'.$data['phone_number'])->where('group_id',$data['group_id'])->whereDate('date_taken',Carbon::today())->get());  
        
        if($ticketcount<4){ 
           
            
            $ticket_id =  count(TblTickets::where('group_id', $data['group_id'])->whereDate('date_taken',Carbon::today())->where('tk_online',1)->get())+1;
            $newticketid = "I$ticket_id";
            $groupLetter = TblGroups::where('group_id', $data['group_id'])->pluck('ticketLetter')->first();
            $max_ticket_uid = TblTickets::latest('ticket_uid')->pluck('ticket_uid')->first();
            $newbarcode = "$groupLetter"."I$max_ticket_uid";
            

            $ticket = TblTickets::create([
                             'ticket_id'    => $newbarcode,
                             'ticket_no'    => $newticketid,
                             'ticket_info'  => $data['fullname'],
                             'ticket_mobile'=> '+993'.$data['phone_number'],
                             'group_id'     => $data['group_id'],
                             'date_taken'   => now(),
                             'time_taken'   => now(),
                             'tcall'        => 0,
                             'tinOut'       => 0,
                             'state'        => 1,
                             'ended'        => 0,
                             'transfered'   => 0,
                             'wait_day'     => 0,
                             'wait_long'    => 0,
                             'device_id'    => null,
                             'call_username'=> '',
                             'date_call'    => '1900-1-1',
                             'date_start'   => '1900-1-1',
                             'date_end'     => '1900-1-1',
                             'sms_sended'   => 0,
                             'date_sms'     => '1900-1-1',
                             'ticket_lang'  => 'tm',
                             'tk_online'    =>1
                          ]);
            if($ticket){

                return response ()->json(['message'=>'success']);
            }
                return response ()->json(['message'=>'failed']);

        }
        //TblTickets::whereDateNot('date_taken', Carbon::today())->remove();
    }
   
    //store ticket end

    //ticket status start
    public function ticket_status(Request $request){

         $data = $request->all();

         $tickets =  TblTickets::where('group_id', $data['group_id'])->
                                 //where('tcall', false)->
                                 whereDate('time_taken', '=',Carbon::today())->
                                 select('time_taken','ticket_no')->
                                 get();
         $my_ticket = TblTickets::where('group_id', $data['group_id'])->
                                  //where('tcall', false)->
                                  where('ticket_mobile', '+993'.$data['phone_number'])->
                                  whereDate('time_taken', '=', Carbon::today())->
                                  select('time_taken','ticket_no')->
                                  get();

         return response()->json([ 'tickets'=>$tickets, 'my_ticket'=>$my_ticket ]);
    }
    //ticket status end
    //my ticket start
    public function my_tickets(Request $request){

        $data = $request->all();

        $my_ticket = TblTickets::where('ticket_mobile', '+993'.$data['phone_number'])->
                                 whereDate('time_taken', '=',Carbon::today())->
                                 select('time_taken','ticket_no')->
                                 get();

        return response()->json([ 'my_ticket'=>$my_ticket ]);
    }
    //my ticket end
}
