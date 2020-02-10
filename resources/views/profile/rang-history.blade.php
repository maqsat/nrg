@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">{{ __('app.processing') }}</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->

            <div class="row">
                <!-- Column -->
                <div class="col-lg-4 col-md-4">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-info">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0 text-info">{{ \App\Facades\Hierarchy::qvCounterAll(Auth::user()->id) }}</h3>
                                <h5 class="text-muted m-b-0">Текущий QV</h5></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-4 col-md-4">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-success">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0 text-success">{{ \App\Facades\Hierarchy::qvCounterAllLastMonth(Auth::user()->id) }}</h3>
                                <h5 class="text-muted m-b-0">Прошлый месяц</h5></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-4 col-md-4">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-inverse">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0">{{ \App\Models\Status::find($userProgram->status_id+1)->qv }}</h3>
                                <h5 class="text-muted m-b-0">QV следующего ранга</h5></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">

                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>Сумма</th>
                                        <th>Месяц</th>
                                        <th>Первая ветка</th>
                                        <th>Вторая ветка</th>
                                        <th>Третья ветка</th>
                                        <th>QV за регистрацию</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($list as $item)
                                        <tr>
                                            <td><span class="text-success">{{ $item->sum }} QV</span></td>
                                            <td class="txt-oflo"><b>{{ date( "Y", strtotime($item->created_at)) }} год, {{ date( "m", strtotime($item->created_at)) }} месяц</b></td>
                                            <td>{{ \App\Facades\Hierarchy::qvCounterByMonth(Auth::user()->id,1,date("m", strtotime($item->created_at))) }} QV</td>
                                            <td>{{ \App\Facades\Hierarchy::qvCounterByMonth(Auth::user()->id,2,date("m", strtotime($item->created_at))) }} QV</td>
                                            <td>{{ \App\Facades\Hierarchy::qvCounterByMonth(Auth::user()->id,3,date("m", strtotime($item->created_at))) }} QV</td>
                                            <td>{{ \App\Models\Counter::where('user_id',Auth::user()->id)->where('position',0)->first()->sum }} QV</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End PAge Content -->
            <!-- ============================================================== -->
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
