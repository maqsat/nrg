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
                                <h3 class="m-b-0 text-info">{{ $balance }}$</h3>
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
                                <h3 class="m-b-0 text-info">{{ $week }}$</h3>
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
                                <h3 class="m-b-0 text-success">{{ $out }}$</h3>
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
                                <h3 class="m-b-0">{{ $all }}$</h3>
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

                        @include('profile.processing.actions')

                        <div class="table-responsive">
                            <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                                <thead>
                                <tr>
                                    <th>ID #</th>
                                    <th>Статус</th>
                                    <th>Сумма</th>
                                    <th>От кого</th>
                                    <th>Пакет дистрибютора</th>
                                    <th>Номер карты</th>
                                    <th>Дата</th>
                                    <th>Ваш Ранг</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($list as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td>
                                            @if($item->status == 'invite_bonus')
                                                <span class="label label-rouded label-warning">Реферальный бонус</span>
                                            @elseif($item->status == 'cashback')
                                                <span class="label label-rouded label-info">Кэшбек</span>
                                            @elseif($item->status == 'turnover_bonus')
                                                <span class="label label-rouded label-info">Бонус за бинар</span>
                                            @elseif($item->status == 'status_bonus')
                                                <span class="label label-rouded label-warning">Бонус признания</span>
                                            @elseif($item->status == 'quickstart_bonus')
                                                <span class="label label-rouded label-warning">Быстрый старт</span>
                                            @elseif($item->status == 'matching_bonus')
                                                <span class="label label-rouded label-warning">Матчинг бонус</span>
                                            @elseif($item->status == 'request')
                                                <span class="label label-rouded label-danger">Запрос на списание вернул ошибку</span>
                                            @elseif($item->status == 'out')
                                                <span class="label label-rouded label-success">Выведено</span>
                                            @else
                                                <span class="label label-rouded label-primary">Не определено</span>
                                            @endif
                                        </td>
                                        <td><span class="text-success">{{ round($item->sum,2) }} $</span></td>
                                        <td class="txt-oflo">@if($item->in_user != 0) {{ \App\User::find($item->in_user)->name }} @endif</td>
                                        <td class="txt-oflo">@if($item->in_user != 0) {{ \App\Models\Package::find($item->package_id)->title }} @endif</td>
                                        <td>{{ $item->card_number }}</td>
                                        <td class="txt-oflo">{{ $item->created_at }}</td>
                                        <td class="txt-oflo">@if(!is_null(\App\Models\Status::find($item->status_id))){{ \App\Models\Status::find($item->status_id)->title }}@endif</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {{ $list->links() }}
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
