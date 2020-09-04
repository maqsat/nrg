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

                    @if(is_null($orders) or $orders->status != 11)
                        <div class="card">
                            <div class="card-block">
                                <div class="row pricing-plan">

                                    @if(count($packages) == 0)
                                        <h3 class="text-success"><i class="fa fa-exclamation-triangle"></i> У вас самый большой пакет!</h3>
                                    {{--@elseif($diff > 0)
                                        <h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> На апгрейд даеться ровно 30 дней с момента регистрации!</h3>--}}
                                    @else
                                        @foreach($packages as $item)
                                            @php
                                                if(!is_null($current_package)) {
                                                    $current_package_cost = $current_package->cost;
                                                    $current_package_pv = $current_package->pv;
                                                    $current_package_id = $current_package->id;
                                                }
                                                else  {
                                                    $current_package_cost = 0;
                                                    $current_package_pv = 0;
                                                    $current_package_id = 0;
                                                }

                                            @endphp
                                            <div class="col-md-3 col-xs-12 col-sm-6 no-padding">
                                                <div class="pricing-box   @if($item->id == 3) featured-plan @endif">
                                                    <div class="pricing-body b-r">
                                                        <div class="pricing-header">
                                                            @if($item->id == 3) <h4 class="price-lable text-white bg-warning"> Popular</h4>@endif
                                                            <h4 class="text-center">{{ $item->title }}</h4>
                                                            <h2 class="text-center"><span class="price-sign">$</span>{{ $item->cost-$current_package_cost }}</h2>
                                                        </div>
                                                        <div class="price-table-content">
                                                            <div class="price-row"><i class="fa fa-product-hunt"></i> {{ $item->pv-$current_package_pv }} PV</div>
                                                            <div class="price-row"><i class="fa fa-money"></i> {{ $item->income }}</div>
                                                            <div class="price-row"><i class="fa fa-star"></i> {{ $item->statusName->title }}</div>
                                                            <div class="price-row"><i class="fa fa-shopping-basket"></i> {{ $item->goods }}</div>
                                                            <div class="price-row">
                                                                <a href="/pay-types?package={{ $item->id }}&upgrade={{$current_package_id}}">
                                                                    <button class="btn btn-success waves-effect waves-light m-t-20">Выбрать пакет и перейти на оплату</button>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
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
