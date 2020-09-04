@extends('layouts.admin')

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
                    <h3 class="text-themecolor m-b-0 m-t-0">Заказы</h3>
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
                                        <th>Клиент</th>
                                        <th>Тип</th>
                                        <th>Купленные товары</th>
                                        <th>Цена</th>
                                        <th>Статус оплаты</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($orders as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->user["name"] }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td><a href="/basket?id={{ $item->id }}" target="_blank">Посмотреть товары</a></td>
                                            <td>${{ $item->amount }}</td>
                                            <td class="actions">
                                                <a href="/success-basket-status/{{ $item->basket_id }}" target="_blank" class="btn btn-success"><i class="mdi mdi-account-plus"></i></a>
                                                @if(!is_null($item) && $item->status == 11)
                                                    <a href="{{asset($item->scan)}}" target="_blank" class="btn btn-primary"><i class="mdi mdi-account-search"></i></a>
                                                    <a href="/cancel-basket-status/{{ $item->basket_id }}" target="_blank" class="btn btn-danger"><i class="mdi mdi-account-remove"></i></a>
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
    <script>
        $('select').change(function ()
        {
            if(confirm("Вы уверены?")){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'POST',
                    url:'/changedeliverystatus',
                    data: {
                        order_id:this.id,
                        delivery_value:this.value,
                    },
                    success:function(data){
                        if(data.status == true){
                            alert('успешно изменен');
                        }
                        else{
                            alert('что то пошло не так ')
                        }
                    }
                });
            }
        });

        function deleteAlert() {
            if(!confirm("Вы уверены что хотите удалить?"))
                event.preventDefault();
        }
    </script>

    @if (session('status'))
        <script>
            $.toast({
                heading: 'Результат действии',
                text: '{{ session('status') }}',
                position: 'top-left',
                loaderBg:'#ffffff',
                icon: 'warning',
                hideAfter: 30000,
                stack: 6
            });
        </script>
    @endif
@endpush

