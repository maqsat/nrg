<div class="row tree">

    <?php $list = \App\User::where('sponsor_id',$current_user->id)->where('status',1)->get();
    ?>

    @foreach($list as $item)
    <div class="col-md-3 col-sm-3 col-xl-3">
        @if(!is_null($item))
            <div class="card card-outline-info">
                <div class="card-header"><i class="mdi mdi-account-switch"></i> {{ $item->login }} ({{ \App\Models\UserProgram::where('list','like','%,'.$item->id.',%')->count() }})</div>
                <div class="card-block">
                    <p class="card-text"><strong>ID: {{ $item->id }}</strong>.
                        {!! view('profile.step-title',['program' => $program,'user' => $item,'user_program' => $user_program]) !!}
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($item->mentor_id)))
                            <strong>Спонсор:</strong>: {{ App\User::find($item->mentor_id)->login }}
                        @endif
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($item->sponsor_id)))
                            <strong>Наставник:</strong>: {{ App\User::find($item->sponsor_id)->login }}
                        @endif
                    </p>
                    <a href="/tree/{{ $item->id }}" class="btn btn-primary">Посмотреть</a>
                </div>
            </div>
        @endif
    </div>
    @endforeach
</div>