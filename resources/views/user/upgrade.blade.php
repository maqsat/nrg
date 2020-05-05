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
                    <h3 class="text-themecolor m-b-0 m-t-0">Пользователи</h3>
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
                                <table class="table color-table success-table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Логин</th>
                                        <th>Спонсор</th>
                                        <th>Дата заявки</th>
                                        <th>Сумма</th>
                                        <th>Текущий пакет</th>
                                        <th>Новый пакет</th>
                                        <th>Upgrade</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        @php
                                            $sponsor = \App\User::find($item->sponsor_id);
                                            $inviter = \App\User::find($item->inviter_id);
                                            $package = \App\Models\Package::find($item->package_id);
                                            $user_program = \App\Models\UserProgram::where('user_id',$item->id)->first();
                                            $order = \App\Models\Order::where('user_id', $item->id)->where('type','upgrade')->where('payment','manual')->orderBy('id','desc')->first();
                                            $package_new = \App\Models\Package::find($order->package_id);
                                        @endphp

                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <b>Наставник</b>: {{ is_null($sponsor) ? '' : $sponsor->name }}<br>
                                                <b>Спонсор</b>: {{ is_null($inviter) ? '' : $inviter->name }}
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($order->created_at)) }}</td>
                                            <td>{{ $order->amount }}$</td>
                                            <td>{{ is_null($package) ? '' : $package->title }}</td>
                                            <td>{{ is_null($package_new) ? '' : $package_new->title }}</td>
                                            <td class="actions">
                                                @if(!is_null($order) && $order->status == 11)
                                                    <a href="/upgrade-activation/{{ $order->id }}" target="_blank" class="btn btn-xs btn-success"><i class="mdi mdi-account-plus"></i></a>
                                                    <a href="{{asset($order->scan)}}" target="_blank" class="btn btn-xs btn-primary"><i class="mdi mdi-account-search"></i></a>
                                                    <a href="/upgrade-deactivation/{{ $order->id }}" target="_blank" class="btn btn-xs btn-danger"><i class="mdi mdi-account-remove"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($_GET['program']))
                                {{ $list->appends(['program' => $_GET['program']])->links() }}
                            @elseif(isset($_GET['non_activate']))
                                {{ $list->appends(['non_activate' => $_GET['non_activate']])->links() }}
                            @elseif(isset($_GET['non_activate']))
                                {{ $list->appends(['non_activate' => $_GET['s']])->links() }}
                            @else
                                {{ $list->links() }}
                            @endif
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

