<?php
    $status_user_program = \App\Models\UserProgram::whereUserId($user->id)->first();
?>
<strong>
    Ранг
    @if(!is_null($status_user_program))
        {{ \App\Models\Status::whereId($status_user_program->status_id)->first()->title }}
    @endif
</strong>