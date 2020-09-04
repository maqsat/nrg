<div class="row tree">
    <div class="col-md-4 col-sm-4 col-xl-4">
        @if(!is_null($current_user_first))
            <div class="card card-outline-info">
                <div class="card-header"><i class="mdi mdi-account-switch"></i> {{ $current_user_first->name }} ({{ \App\Models\UserProgram::where('list','like','%,'.$current_user_first->id.',%')->count() }})</div>
                <div class="card-block">
                    <p class="card-text"><strong>ID: {{ $current_user_first->id }}</strong>.
                        {!! view('profile.step-title',['program' => $program,'user' => $current_user_first,'user_program' => $user_program]) !!}
                    </p>
                    <p class="card-text">
                        <strong>Пакет:</strong> {{ \App\Models\Package::find($current_user_first->package_id)->title }}
                    </p>
                    <p class="card-text">
                        <strong>QV: {{ \App\Facades\Hierarchy::qvCounterForTree($current_user_first->id,$current_user_first->position) }}</strong>
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($current_user_first->inviter_id)))
                            <strong>Спонсор:</strong> {{ App\User::find($current_user_first->inviter_id)->name }}
                        @endif
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($current_user_first->sponsor_id)))
                            <strong>Наставник:</strong> {{ App\User::find($current_user_first->sponsor_id)->name }}
                        @endif
                    </p>
                    <a href="/tree/{{ $current_user_first->id }}" class="btn btn-primary">Посмотреть</a>
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-4 col-sm-4 col-xl-4">
        @if(!is_null($current_user_second))
            <div class="card  card-outline-info">
                <div class="card-header"><i class="mdi mdi-account-switch"></i> {{ $current_user_second->name }} ({{ \App\Models\UserProgram::where('list','like','%,'.$current_user_second->id.',%')->count() }})</div>
                <div class="card-block">
                    <p class="card-text"><strong>ID: {{ $current_user_second->id }}</strong>.
                        {!! view('profile.step-title',['program' => $program,'user' => $current_user_second,'user_program' => $user_program]) !!}
                    </p>
                    <p class="card-text">
                        <strong>Пакет:</strong> {{ \App\Models\Package::find($current_user_second->package_id)->title }}
                    </p>
                    <p class="card-text">
                        <strong>QV: {{ \App\Facades\Hierarchy::qvCounterForTree($current_user_second->id,$current_user_second->position) }}</strong>
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($current_user_second->inviter_id)))
                            <strong>Спонсор:</strong> {{ App\User::find($current_user_second->inviter_id)->name }}
                        @endif
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($current_user_second->sponsor_id)))
                            <strong>Наставник:</strong> {{ App\User::find($current_user_second->sponsor_id)->name }}
                        @endif
                    </p>
                    <a href="/tree/{{ $current_user_second->id }}" class="btn btn-primary">Посмотреть</a>
                </div>
            </div>
        @endif
    </div>
    <div class="col-md-4 col-sm-4 col-xl-4">
        @if(!is_null($current_user_third))
            <div class="card  card-outline-info">
                <div class="card-header"><i class="mdi mdi-account-switch"></i> {{ $current_user_third->name }} ({{ \App\Models\UserProgram::where('list','like','%,'.$current_user_third->id.',%')->count() }})</div>
                <div class="card-block">
                    <p class="card-text"><strong>ID: {{ $current_user_third->id }}</strong>.
                        {!! view('profile.step-title',['program' => $program,'user' => $current_user_third,'user_program' => $user_program]) !!}
                    </p>
                    <p class="card-text">
                        <strong>Пакет:</strong> {{ \App\Models\Package::find($current_user_third->package_id)->title }}
                    </p>
                    <p class="card-text">
                        <strong>QV: {{ \App\Facades\Hierarchy::qvCounterForTree($current_user_third->id,$current_user_third->position) }}</strong>
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($current_user_third->inviter_id)))
                            <strong>Спонсор:</strong> {{ App\User::find($current_user_third->inviter_id)->name }}
                        @endif
                    </p>
                    <p class="card-text">
                        @if(!is_null(App\User::find($current_user_third->sponsor_id)))
                            <strong>Наставник:</strong> {{ App\User::find($current_user_third->sponsor_id)->name }}
                        @endif
                    </p>
                    <a href="/tree/{{ $current_user_third->id }}" class="btn btn-primary">Посмотреть</a>
                </div>
            </div>
        @endif
    </div>
</div>