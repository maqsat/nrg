<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $guarded = [];

    static function pushLog($data = [])
    {
        if(!empty($data)) {
            $initiator = User::find($data['initiator']);
            $initiator_up = UserProgram::where('user_id', $data['initiator'])->first();
            $recipient = User::find($data['recipient']);
            $recipient_up = UserProgram::where('user_id', $data['recipient'])->first();
            $log = new Log(array_merge($data, [
                'initiator_sponsor' => $initiator->sponsor_id,
                'initiator_inviter' => $initiator->inviter_id,
                'up_initiator_sponsor'  => $initiator_up->list,
                'up_initiator_inviter'  => $initiator_up->inviter_list,
                'recipient_sponsor' => $recipient->sponsor_id,
                'recipient_inviter' => $recipient->inviter_id,
                'up_recipient_sponsor'  => $recipient_up->list,
                'up_recipient_inviter'  => $recipient_up->inviter_list,
            ]));
            $log->save();
        }

    }
}
