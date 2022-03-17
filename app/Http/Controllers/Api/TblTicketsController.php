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
    public function store(Request $request){

        $ticketcount = $this->checkTicketForDouble($request);
        if($ticketcount<4){
            $ticket_id = $this->get_online_ticket_id($request);
            $newticketid = "I$ticket_id";
            $groupLetter = $this->get_groupLetter($request);
            $max_ticket_uid = $this->get_max_online_tickets();
            $newbarcode = "$groupLetter"."I$max_ticket_uid";
            $lastid = $this->addticket($newbarcode,$newticketid,$request);return $lastid;
        }
        $this->deleteOldTickets();
    }

    public function checkTicketForDouble($request)
    {

        $count = count(TblTickets::where('ticket_mobile', '+'.$request->phone_number)->
                                   where('group_id',$request->group_id)->
                                   whereDate('date_taken',Carbon::today())->get());
        return $count;
    }

    public function get_online_ticket_id($request)
    {

        $online_ticket_id = count(TblTickets::where('group_id', $request->group_id)->
                                              whereDate('date_taken',Carbon::today())->
                                              where('tk_online',1)->get())+1;

        return $online_ticket_id;
    }

    public function get_groupLetter($request){

        $letter = TblGroups::where('group_id', $request->group_id)->select('ticketLetter')->first();
        return $letter->ticketLetter;
    }

    public function get_max_online_tickets(){

        return TblTickets::latest('ticket_uid')->pluck('ticket_uid')->first();
    }

    public function addticket($mainid,$ticketinfoid,$request)
    {
        $ticket = TblTickets::create([
                             'ticket_id'    => $mainid,
                             'ticket_no'    => $ticketinfoid,
                             'ticket_info'  => $request->fullname,
                             'ticket_mobile'=> '+'.$request->phone_number,
                             'group_id'     => $request->group_id,
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
        return $ticket->id;
    }

    public function deleteOldTickets()
    {
        TblTickets::whereDateNot('date_taken', Carbon::today())->remove();
    }

    //store ticket end

    //ticket status start
    public function ticket_status(Request $request){

         $tickets =  TblTickets::where('group_id', $request->group_id)->
                                 whereDate('date_taken', Carbon::today())->
                                 select('time_taken','ticket_no')->
                                 get();
         $my_ticket = TblTickets::where('group_id', $request->group_id)->
                                  where('ticket_mobile', $request->phone_number)->
                                  whereDate('date_taken', Carbon::today())->
                                  select('time_taken','ticket_no')->
                                  get();

         return response()->json([ 'tickets'=>$tickets, 'my_ticket'=>$my_ticket ]);
    }
    //ticket status end

    public function my_tickets(Request $request){

        $my_ticket = TblTickets::where('ticket_mobile', $request->phone_number)->
                                 select('time_taken','ticket_no')->
                                 get();

        return response()->json([ 'my_ticket'=>$my_ticket ]);

    }
}
