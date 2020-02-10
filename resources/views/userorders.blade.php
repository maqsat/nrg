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
                    <h3 class="text-themecolor m-b-0 m-t-0">Мои заказы</h3>
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
                                        <th>Наименование</th>
                                        <th>Тип</th>
                                        <th>Пакет</th>
                                        <th>Купленные товары</th>
                                        <th>Цена</th>
                                        <th>Статус заказа</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->user["name"] }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td>{{ isset($item->user->package)?$item->user->package["title"]:'' }}</td>
                                            <td>
                                                @if($item->basket_id)
                                                    <a href="/basket_items/{{$item->basket_id}}" target="_blank" class="btn btn-info">
                                                        Посмотреть
                                                    </a>
                                                @else
                                                    Пакет
                                                @endif
                                            </td>
                                            <td>{{ $item->amount }} тенге</td>
                                            <td>
                                                @if($item->delivery_status)
                                                    Доставлен
                                                @else
                                                    Не доставлен
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $orders->links() }}
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


@push('styles')
    <style>
        .table td, .table th {
            padding: .25rem .15rem;
        }
    </style>
@endpush

@push('scripts')
@endpush

