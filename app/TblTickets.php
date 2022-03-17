<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblTickets extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'ticket_id',
        'ticket_no',
        'ticket_info',
        'ticket_mobile',
        'group_id',
        'date_taken',
        'time_taken',
        'tcall',
        'tinOut',
        'state',
        'ended',
        'transfered',
        'wait_day',
        'wait_long',
        'device_id',
        'call_username',
        'date_call',
        'date_start',
        'date_end',
        'sms_sended',
        'date_sms',
        'ticket_lang',
        'tk_online'
    ];
}
