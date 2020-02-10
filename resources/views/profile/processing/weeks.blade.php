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
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-info">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0 text-info">{{ $balance }}$/{{ round($balance*385) }} ₸</h3>
                                <h5 class="text-muted m-b-0">Доступная сумма</h5></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-primary">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0 text-info">{{ $week }}$/{{ round($week*385) }} ₸</h3>
                                <h5 class="text-muted m-b-0">В блоке</h5></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-success">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0 text-success">{{ $out }}$/{{ round($out*385) }} ₸</h3>
                                <h5 class="text-muted m-b-0">Выведено</h5></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-inverse">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0">{{ $all }}$/{{ round($all*385) }} ₸</h3>
                                <h5 class="text-muted m-b-0">Оборот</h5></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">
                            <div id="accordion2" role="tablist" class="minimal-faq" aria-multiselectable="true">
                                @foreach($weeks as $key =>$item)
                                    @if(isset($weeks[$key+1]))
                                        <div class="card m-b-0">
                                            <div class="card-header" role="tab" id="headingOne{{$key}}">
                                                <h5 class="mb-0">
                                                    <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne{{$key}}" aria-expanded="true" aria-controls="collapseOne{{$key}}">
                                                       <b>{{ str_replace('-','.',$weeks[$key+1]) }} – {{ str_replace('-','.',$item) }} @if($key == 0) (В блоке) @endif</b>
                                                    </a>
                                                </h5>
                                            </div>
                                            <div id="collapseOne{{$key}}" class="collapse  @if($key == 0) show @endif" role="tabpanel" aria-labelledby="headingOne{{$key}}">
                                                <div class="table-responsive">
                                                    <table id="demo-foo-addrow" class="display nowrap table table-hover" data-page-size="10">
                                                        <thead>
                                                        <tr>
                                                            <th>ID #</th>
                                                            <th>Статус</th>
                                                            <th>Сумма</th>
                                                            <th>Действие</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Кэшбек</td>
                                                                <td>{{ \App\Facades\Balance::getWeekBalanceByStatus(Auth::user()->id,$weeks[$key+1],$item,'cashback') }}$</td>
                                                                <td><a href="">Подробнее</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>Матчинг бонус</td>
                                                                <td>{{ \App\Facades\Balance::getWeekBalanceByStatus(Auth::user()->id,$weeks[$key+1],$item,'matching_bonus') }}$</td>
                                                                <td><a href="">Подробнее</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>Бонус за бинар</td>
                                                                <td>{{ \App\Facades\Balance::getWeekBalanceByStatus(Auth::user()->id,$weeks[$key+1],$item,'turnover_bonus') }}$</td>
                                                                <td><a href="">Подробнее</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td>Бонус признания</td>
                                                                <td>{{ \App\Facades\Balance::getWeekBalanceByStatus(Auth::user()->id,$weeks[$key+1],$item,'status_bonus') }}$</td>
                                                                <td><a href="">Подробнее</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td>Реферальный бонус</td>
                                                                <td>{{ \App\Facades\Balance::getWeekBalanceByStatus(Auth::user()->id,$weeks[$key+1],$item,'invite_bonus') }}$</td>
                                                                <td><a href="">Подробнее</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td>6</td>
                                                                <td>Быстрый старт</td>
                                                                <td>{{ \App\Facades\Balance::getWeekBalanceByStatus(Auth::user()->id,$weeks[$key+1],$item,'quickstart_bonus') }}$</td>
                                                                <td><a href="">Подробнее</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td><h4>Итого</h4></td>
                                                                <td><h4>{{ \App\Facades\Balance::getWeekBalanceByRange(Auth::user()->id,$weeks[$key+1],$item) }}$</h4></td>
                                                                <td></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
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

@push('scripts')
@if (session('status'))

    <script src="/monster_admin/main/js/toastr.js"></script>
    <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
    <script>
        $.toast({
            heading: 'Вывод средств',
            text: '{{ session('status') }}',
            position: 'top-right',
            loaderBg:'#ffffff',
            icon: 'error',
            hideAfter: 60000,
            stack: 6
        });
    </script>
@endif
@endpush

@push('styles')
<link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
@endpush
