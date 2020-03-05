@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if(!is_null($orders))
                        @if($orders->status == 11)
                            <div class="alert alert-warning">
                                <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> Квитанция находится на проверке</h3>
                                Статус модерации:  Квитанция отправлено на проверку <br>
                                Сумма оплаты: ${{ $orders->amount }} <br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата отправки: {{ $orders->updated_at }}
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Квитанция отклонена</h3>
                                Статус модерации:  Фейковая квитанция <br>
                                Сумма оплаты: ${{ $orders->amount }} <br>
                                @if($orders->package_id != 0)
                                    Выбранный пакет: {{ \App\Models\Package::find($orders->package_id)->title }} <br>
                                @endif
                                Дата ответа: {{ $orders->updated_at }} <br>
                                Квитанция:  <a href="{{asset($orders->scan)}}" target="_blank" class="btn btn-xs btn-danger">Посмотреть</a>
                            </div>
                        @endif
                    @endif


                    @if(is_null($orders) or $orders->status == 12)
                            @if(!isset($fk))
                        @if(is_null($orders) or $orders->status != 12)<div class="alert alert-danger">
                            <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Примечание!</h3> Вам необходимо оплатить регистрационный сбор и выбрать пакет. У вас есть 24 часа чтобы активировать кабинет, по истечению срока ваш кабинет удалится.
                        </div>@endif
                        <div class="col-lg-12 col-md-12 p-0">
                            <div class="card">
                                <div class="d-flex flex-row">
                                    <div class="p-10 bg-success">
                                        <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3>
                                    </div>
                                    <div class="align-self-center m-l-20 pay_button">
                                        <h3 class="m-b-0 text-success">${{ env('REGISTRATION_FEE') }}</h3>
                                        <h5 class="text-muted m-b-0">Регистрационный сбор</h5>
                                    </div>
                                    <a href="/pay-types">
                                        <button class="btn btn-success waves-effect waves-light m-t-15 m-b-15 m-r-10">Оплатить без БИЗНЕС ПАКЕТА</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                            @endif
                        <div class="card">
                            <div class="card-block">
                                <div class="row pricing-plan">
                                    @foreach(\App\Models\Package::where('status',1)->get() as $item)
                                        <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                                            <div class="pricing-box   @if($item->id == 3) featured-plan @endif">
                                                <div class="pricing-body b-r">
                                                    <div class="pricing-header">
                                                        @if($item->id == 3) <h4 class="price-lable text-white bg-warning"> Popular</h4>@endif
                                                        <h4 class="text-center">{{ $item->title }}</h4>
                                                        <h2 class="text-center"><span class="price-sign">$</span>{{ $item->cost }}</h2>
                                                    </div>
                                                    <div class="price-table-content">
                                                        <div class="price-row"><i class="fa fa-product-hunt"></i> {{ $item->pv }} PV</div>
                                                        <div class="price-row"><i class="fa fa-money"></i> {{ $item->income }}</div>
                                                        <div class="price-row"><i class="fa fa-star"></i> {{ $item->statusName->title }}</div>
                                                        <div class="price-row"><i class="fa fa-shopping-basket"></i> {{ $item->goods }}</div>
                                                        <div class="price-row">
                                                            <a href="/pay-types?package={{ $item->id }}">
                                                                <button class="btn btn-success waves-effect waves-light m-t-20">Выбрать пакет и перейти на оплату</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        @include('layouts.footer')
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection
