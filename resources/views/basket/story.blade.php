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
                    <h3 class="text-themecolor m-b-0 m-t-0">Покупки</h3>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table class="table color-table success-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Дата покупки</th>
                                        <th>Сумма покупки</th>
                                        <th>Сумма CV</th>
                                        <th>Количество товаров</th>
                                        <th>Товары</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $key => $item)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $item->updated_at }}</td>
                                            <?php

                                                $order_pv = \App\Models\Order::join('baskets','baskets.id','=','orders.basket_id')
                                                    ->join('basket_good','basket_good.basket_id','=','baskets.id')
                                                    ->join('products','basket_good.good_id','=','products.id')
                                                    ->where('orders.type','shop')
                                                    ->where('orders.basket_id',$item->id)
                                                    ->where('orders.not_original',null)
                                                    ->groupBy('basket_good.good_id')
                                                    ->select([DB::raw('basket_good.quantity * products.pv as sum'),DB::raw('basket_good.quantity * products.partner_cost as amount'),'basket_good.quantity'])
                                                    ->get();

                                                    $sum_pv = 0;
                                                    foreach ($order_pv as $pv){
                                                        $sum_pv +=$pv->sum;
                                                    }

                                                    $amount = 0;
                                                    foreach ($order_pv as $pv){
                                                        $amount +=$pv->amount;
                                                    }

                                                    $quantity = 0;
                                                    foreach ($order_pv as $pv){
                                                        $quantity +=$pv->quantity;
                                                    }
                                            ?>
                                            <td>{{ $amount }}$</td>
                                            <td>{{ $sum_pv }} cv</td>
                                            <td>{{ $quantity }}</td>
                                            <td><a href="/basket?id={{ $item->id }}" target="_blank">Посмотреть товары</a></td>
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
        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        @include('layouts.footer')
    </div>
@endsection

@section('body-class')
    fix-header card-no-border fix-sidebar
@endsection

@push('scripts')

@endpush

