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
                    <h3 class="text-themecolor m-b-0 m-t-0">Клиенты</h3>
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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-block">
                            <form action="/user">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="s" placeholder="Поиск по полям логин, спонсор, имя ..." value="{{ old('s') }}">
                                            <span class="input-group-btn">
                                                <button class="btn btn-info" type="submit">Искать!</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <!-- form-group -->
                            </form>
                            <div class="table-responsive">
                                <table class="table color-table success-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Логин</th>
                                        <th>Телефон</th>
                                        <th>Действие</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                <a href="" class="btn btn-info">
                                                    @if(in_array($item->id, $userclientsarray))
                                                        {{client_phone}}
                                                    @else<i class="mdi mdi-eye"></i>
                                                    @endif
                                                </a>
                                            </td>
                                            <td class="actions">
                                                @if(!in_array($item->id, $userclientsarray))
                                                    <a href = "javascript:void(0);"  class="btn btn-success"
                                                       onclick="addClientToBasket({{ $item->id }},{{Auth::user()->id}},true,false)">
                                                    Добавить в корзину</a>
                                                @else
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $list->links() }}
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
            padding: 10px 15px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function deleteAlert() {
            if(!confirm("Вы уверены что хотите удалить?"))
                event.preventDefault();
        }
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function addClientToBasket(good,user,add,del) {
            $.ajax({
                type:'POST',
                url:'/buycontact',
                data: {good_id:good, user_id:user ,add:add,delete:del },
                success:function(data){
                    if(data.status == true){
                        $.toast({
                            heading: 'Товар добавлен в корзину!',
                            text: 'Товар добавлен в корзину! Что бы оплатить перейдите в корзину',
                            position: 'bottom-right',
                            loaderBg:'#ff6849',
                            icon: 'success',
                            hideAfter: 30000,
                            stack: 6
                        });
                    }
                }
            });

            var selector = "btn"+good;
            document.getElementById(selector).innerHTML = "Добавить еще раз"

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

