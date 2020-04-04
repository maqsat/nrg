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
                                        <th>Позиция</th>
                                        <th>Акт/ия</th>
                                        <th>Статус</th>
                                        <th>Регистрация</th>
                                        <th>Баланс</th>
                                        <th>Пакет</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        @php
                                            $sponsor = \App\User::find($item->sponsor_id);
                                            $inviter = \App\User::find($item->inviter_id);
                                            $package = \App\Models\Package::find($item->package_id);
                                            $user_program = \App\Models\UserProgram::where('user_id',$item->id)->first();
                                            $order = \App\Models\Order::where('user_id', $item->id)->where('type','register')->orderBy('id','desc')->first();
                                        @endphp

                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>
                                                <b>Наставник</b>: {{ is_null($sponsor) ? '' : $sponsor->name }}<br>
                                                <b>Спонсор</b>: {{ is_null($inviter) ? '' : $inviter->name }}
                                            </td>
                                            <td>@if($item->position == 1) Слева @else Справа @endif</td>
                                            @if($item->status == 1)
                                                <td class="actions"><a class="btn btn-xs btn-info"><i class="mdi mdi-account-check"></i></a></td>
                                            @else
                                                <td class="actions">
                                                    <a href="/activation/{{ $item->id }}" target="_blank" class="btn btn-xs btn-success"><i class="mdi mdi-account-plus"></i></a>
                                                    @if(!is_null($order) && $order->status == 11)
                                                        <a href="{{asset($order->scan)}}" target="_blank" class="btn btn-xs btn-primary"><i class="mdi mdi-account-search"></i></a>
                                                        <a href="/deactivation/{{ $item->id }}" target="_blank" class="btn btn-xs btn-danger"><i class="mdi mdi-account-remove"></i></a>
                                                    @endif
                                                </td>
                                            @endif
                                            <td class="actions">
                                                @if(!is_null($user_program) && $user_program->status_id != 0)
                                                    {{ \App\Models\Status::whereId($user_program->status_id)->first()->title }}
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ Balance::getBalance($item->id) }}$</td>
                                            <td>{{ is_null($package) ? '' : $package->title }}</td>
                                            <td class="actions">
                                                <a href="/user/{{ $item->id }}/processing" target="_blank" class="btn  btn-xs btn-info"  title="Финансы"><i class="mdi mdi-cash-multiple"></i></a>
                                                <a href="/user/{{ $item->id }}/program" target="_blank" class="btn  btn-xs btn-success"  title="Пакет, статус, офис"><i class="mdi mdi-account-settings-variant"></i></a>
                                                <a href="/user/{{ $item->id }}/transfer" target="_blank" class="btn  btn-xs btn-warning"  title="Перевод"><i class="mdi mdi-sitemap"></i></a>
                                                <a href="/user/{{ $item->id }}" target="_blank" class="btn  btn-xs btn-info"   title="Зайти под"><i class="mdi mdi-eye"></i></a>
                                                <a href="/user/{{ $item->id }}/edit" class="btn  btn-xs btn-success"  title="Изменить"><i class="mdi mdi-grease-pencil" ></i></a>
                                                <form action="{{url('user', [$item->id])}}" method="POST">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn  btn-xs btn-danger" onclick="return deleteAlert();" title="Удалить"><i class="mdi mdi-delete"></i></button>
                                                </form>
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
                            @elseif(isset($_GET['s']))
                                {{ $list->appends(['s' => $_GET['s']])->links() }}
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

