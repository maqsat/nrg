@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid  data-tree">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-12 col-12 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">{{ __('app.tree') }} @if(Route::currentRouteName() == 'tree' && !is_null($current_user)) пользователя -  <b>{{ $current_user->name }}</b> @endif</h3>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <div class="row">
                <div class="col-md-4  offset-md-4 align-self-center">
                    <div class="card card-block">
                        <div class="row">
                            <div class="col-md-4 col-lg-3 text-center">
                                <img src="@if($current_user->photo != '')/{{ $current_user->photo }} @else /monster_admin/assets/images/users/1.jpg @endif" alt="user" class="img-circle img-responsive tree-img">
                            </div>
                            <div class="col-md-8 col-lg-9">
                                <h3 class="box-title m-b-0">{{ $current_user->name }}</h3> <small>{{ \App\Models\Status::find($current_user->status_id)->title }} / @if($current_user->package_id != 0) {{ \App\Models\Package::find($current_user->package_id)->title }} @else Без пакета @endif</small>
                                <address class="m-b-0">
                                    <?php
                                        $left = \App\Facades\Hierarchy::pvCounter($current_user->user_id,1);
                                        $right = \App\Facades\Hierarchy::pvCounter($current_user->user_id,2)
                                    ?>
                                   {{ $left }} PV /  {{ $right }} PV | {{ $left+$right }} PV - всего
                                        <br/>
                                </address>
                                <address class="m-b-0">
                                    <?php
                                    $left_w = \App\Facades\Hierarchy::pvWeekCounter($current_user->user_id,1);
                                    $right_w = \App\Facades\Hierarchy::pvWeekCounter($current_user->user_id,2)
                                    ?>
                                    {{ $left_w }} PV /  {{ $right_w }} PV | {{ $left_w+$right_w }} PV - на этой неделе
                                    <br/>
                                </address>
                                <address class="m-b-0">
                                    <?php
                                    $left_u = \App\Facades\Hierarchy::userCount($current_user->user_id,1);
                                    $right_u = \App\Facades\Hierarchy::userCount($current_user->user_id,2)
                                    ?>
                                    {{ $left_u }} /  {{ $right_u }} | {{ $left_u+$right_u }} - партнеров
                                    <br/>
                                </address>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4  offset-md-1 align-self-center">
                    <div class="card card-block">
                        @if(!is_null($left_user))
                            <a href="/tree/{{ $left_user->user_id }}">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 text-center">
                                        <img src="@if($left_user->photo != '')/{{ $left_user->photo }} @else /monster_admin/assets/images/users/1.jpg @endif" alt="user" class="img-circle img-responsive tree-img">
                                    </div>
                                    <div class="col-md-8 col-lg-9">
                                        <h3 class="box-title m-b-0">{{ $left_user->name }}</h3> <small>{{ \App\Models\Status::find($left_user->status_id)->title }} / @if($left_user->package_id != 0) {{ \App\Models\Package::find($left_user->package_id)->title }} @else Без пакета @endif</small>
                                        <address class="m-b-0">
                                            <?php
                                            $left = \App\Facades\Hierarchy::pvCounter($left_user->user_id,1);
                                            $right = \App\Facades\Hierarchy::pvCounter($left_user->user_id,2)
                                            ?>
                                            {{ $left }} PV /  {{ $right }} PV | {{ $left+$right }} PV
                                                <br/>
                                        </address>
                                        <address class="m-b-0">
                                            <?php
                                            $left_w = \App\Facades\Hierarchy::pvWeekCounter($left_user->user_id,1);
                                            $right_w = \App\Facades\Hierarchy::pvWeekCounter($left_user->user_id,2)
                                            ?>
                                            {{ $left_w }} PV /  {{ $right_w }} PV | {{ $left_w+$right_w }} PV - на этой неделе
                                            <br/>
                                        </address>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md-4 offset-md-2 align-self-center">
                    <div class="card card-block">
                        @if(!is_null($right_user))
                            <a href="/tree/{{ $right_user->user_id }}">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 text-center">
                                        <img src="@if($right_user->photo != '')/{{ $right_user->photo }} @else /monster_admin/assets/images/users/1.jpg @endif" alt="user" class="img-circle img-responsive tree-img">
                                    </div>
                                    <div class="col-md-8 col-lg-9">
                                        <h3 class="box-title m-b-0">{{ $right_user->name }}</h3> <small>{{ \App\Models\Status::find($right_user->status_id)->title }} /@if($right_user->package_id != 0)  {{ \App\Models\Package::find($right_user->package_id)->title }} @else Без пакета @endif</small>
                                        <address class="m-b-0">
                                            <?php
                                            $left = \App\Facades\Hierarchy::pvCounter($right_user->user_id,1);
                                            $right = \App\Facades\Hierarchy::pvCounter($right_user->user_id,2)
                                            ?>
                                            {{ $left }} PV /  {{ $right }} PV | {{ $left+$right }} PV
                                                <br/>
                                        </address>
                                        <address class="m-b-0">
                                            <?php
                                            $left_w = \App\Facades\Hierarchy::pvWeekCounter($right_user->user_id,1);
                                            $right_w = \App\Facades\Hierarchy::pvWeekCounter($right_user->user_id,2)
                                            ?>
                                            {{ $left_w }} PV /  {{ $right_w }} PV | {{ $left_w+$right_w }} PV - на этой неделе
                                            <br/>
                                        </address>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 align-self-center">
                    <div class="card card-block">
                        @if(!is_null($left_user_l))
                            <a href="/tree/{{ $left_user_l->user_id }}">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 text-center">
                                        <img src="@if($left_user_l->photo != '')/{{ $left_user_l->photo }} @else /monster_admin/assets/images/users/1.jpg @endif" alt="user" class="img-circle img-responsive tree-img">
                                    </div>
                                    <div class="col-md-8 col-lg-9">
                                        <h3 class="box-title m-b-0">{{ $left_user_l->name }}</h3> <small>{{ \App\Models\Status::find($left_user_l->status_id)->title }} /@if($left_user_l->package_id != 0)   {{ \App\Models\Package::find($left_user_l->package_id)->title }} @else Без пакета @endif</small>
                                        <address class="m-b-0">
                                            <?php
                                            $left = \App\Facades\Hierarchy::pvCounter($left_user_l->user_id,1);
                                            $right = \App\Facades\Hierarchy::pvCounter($left_user_l->user_id,2)
                                            ?>
                                            {{ $left }} PV /  {{ $right }} PV | {{ $left+$right }} PV
                                            <br/>
                                        </address>
                                        <address class="m-b-0">
                                            <?php
                                            $left_w = \App\Facades\Hierarchy::pvWeekCounter($left_user_l->user_id,1);
                                            $right_w = \App\Facades\Hierarchy::pvWeekCounter($left_user_l->user_id,2)
                                            ?>
                                            {{ $left_w }} PV /  {{ $right_w }} PV | {{ $left_w+$right_w }} PV - на этой неделе
                                            <br/>
                                        </address>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 align-self-center">
                    <div class="card card-block">
                        @if(!is_null($left_user_r))
                            <a href="/tree/{{ $left_user_r->user_id }}">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 text-center">
                                        <img src="@if($left_user_r->photo != '')/{{ $left_user_r->photo }} @else /monster_admin/assets/images/users/1.jpg @endif" alt="user" class="img-circle img-responsive tree-img">
                                    </div>
                                    <div class="col-md-8 col-lg-9">
                                        <h3 class="box-title m-b-0">{{ $left_user_r->name }}</h3> <small>{{ \App\Models\Status::find($left_user_r->status_id)->title }} /@if($left_user_r->package_id != 0)   {{ \App\Models\Package::find($left_user_r->package_id)->title }} @else Без пакета @endif</small>
                                        <address class="m-b-0">
                                            <?php
                                            $left = \App\Facades\Hierarchy::pvCounter($left_user_r->user_id,1);
                                            $right = \App\Facades\Hierarchy::pvCounter($left_user_r->user_id,2)
                                            ?>
                                            {{ $left }} PV /  {{ $right }} PV | {{ $left+$right }} PV
                                            <br/>
                                        </address>
                                        <address class="m-b-0">
                                            <?php
                                            $left_w = \App\Facades\Hierarchy::pvWeekCounter($left_user_r->user_id,1);
                                            $right_w = \App\Facades\Hierarchy::pvWeekCounter($left_user_r->user_id,2)
                                            ?>
                                            {{ $left_w }} PV /  {{ $right_w }} PV | {{ $left_w+$right_w }} PV - на этой неделе
                                            <br/>
                                        </address>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 align-self-center">
                    <div class="card card-block">
                        @if(!is_null($right_user_l))
                            <a href="/tree/{{ $right_user_l->user_id }}">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 text-center">
                                        <img src="@if($right_user_l->photo != '')/{{ $right_user_l->photo }} @else /monster_admin/assets/images/users/1.jpg @endif" alt="user" class="img-circle img-responsive tree-img">
                                    </div>
                                    <div class="col-md-8 col-lg-9">
                                        <h3 class="box-title m-b-0">{{ $right_user_l->name }}</h3> <small>{{ \App\Models\Status::find($right_user_l->status_id)->title }} /@if($right_user_l->package_id != 0)   {{ \App\Models\Package::find($right_user_l->package_id)->title }} @else Без пакета @endif</small>
                                        <address class="m-b-0">
                                            <?php
                                            $left = \App\Facades\Hierarchy::pvCounter($right_user_l->user_id,1);
                                            $right = \App\Facades\Hierarchy::pvCounter($right_user_l->user_id,2)
                                            ?>
                                            {{ $left }} PV /  {{ $right }} PV | {{ $left+$right }} PV
                                            <br/>
                                        </address>
                                        <address class="m-b-0">
                                            <?php
                                            $left_w = \App\Facades\Hierarchy::pvWeekCounter($right_user_l->user_id,1);
                                            $right_w = \App\Facades\Hierarchy::pvWeekCounter($right_user_l->user_id,2)
                                            ?>
                                            {{ $left_w }} PV /  {{ $right_w }} PV | {{ $left_w+$right_w }} PV - на этой неделе
                                            <br/>
                                        </address>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 align-self-center">
                    <div class="card card-block">
                        @if(!is_null($right_user_r))
                            <a href="/tree/{{ $right_user_r->user_id }}">
                                <div class="row">
                                    <div class="col-md-4 col-lg-3 text-center">
                                        <img src="@if($right_user_r->photo != '')/{{ $right_user_r->photo }} @else /monster_admin/assets/images/users/1.jpg @endif" alt="user" class="img-circle img-responsive tree-img">
                                    </div>
                                    <div class="col-md-8 col-lg-9">
                                        <h3 class="box-title m-b-0">{{ $right_user_r->name }}</h3> <small>{{ \App\Models\Status::find($right_user_r->status_id)->title }} /@if($right_user_r->package_id != 0)   {{ \App\Models\Package::find($right_user_r->package_id)->title }} @else Без пакета @endif</small>
                                        <address class="m-b-0">
                                            <?php
                                            $left = \App\Facades\Hierarchy::pvCounter($right_user_r->user_id,1);
                                            $right = \App\Facades\Hierarchy::pvCounter($right_user_r->user_id,2)
                                            ?>
                                            {{ $left }} PV /  {{ $right }} PV | {{ $left+$right }} PV
                                            <br/>
                                        </address>
                                        <address class="m-b-0">
                                            <?php
                                            $left_w = \App\Facades\Hierarchy::pvWeekCounter($right_user_r->user_id,1);
                                            $right_w = \App\Facades\Hierarchy::pvWeekCounter($right_user_r->user_id,2)
                                            ?>
                                            {{ $left_w }} PV /  {{ $right_w }} PV | {{ $left_w+$right_w }} PV - на этой неделе
                                            <br/>
                                        </address>
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
    @include('layouts.footer')
    <!-- ============================================================== -->
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('styles')
    <link href="/css/tree.css" rel="stylesheet">
@endpush
