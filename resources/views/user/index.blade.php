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
                                        @if(Gate::allows('admin_column_pv'))
                                        <th>PV</th>
                                        @endif
                                        <th>Позиция</th>
                                        <th>Акт/ия</th>
                                        <th>Статус</th>
                                        <th>Регистрация</th>
                                        <th>Баланс</th>
                                        <th>Пакет</th>
                                        @if(Gate::allows('admin_actions_user'))
                                        <th>Действие</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        @if($item->admin && $item->role_id == 0)
                                            @continue
                                        @endif
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
                                            @if(Gate::allows('admin_column_pv'))
                                            <td>
                                                @if($item->status == 1)
                                                <b>Слева</b>: {{ Hierarchy::pvCounter($item->id,1) }}<br>
                                                <b>Справа</b>: {{ Hierarchy::pvCounter($item->id,2) }}
                                                @endif
                                            </td>
                                            @endif
                                            <td>@if($item->status == 1) @if($item->position == 1) Слева @else Справа @endif  @endif</td>
                                            @if($item->status == 1)
                                                <td class="actions"><a class="btn btn-xs btn-info"><i class="mdi mdi-account-check"></i></a></td>
                                            @else
                                                <td class="actions">
                                                    @if(Gate::allows('admin_activation_user'))
                                                    <a href="/activation/{{ $item->id }}" target="_blank" class="btn btn-xs btn-success"><i class="mdi mdi-account-plus"></i></a>
                                                    <a href="{{ route('activation_without_bonus', [ 'user_id' => $item->id ]) }}" target="_blank" title="Без бонусов" class="btn btn-xs btn-warning"><i class="mdi mdi-account-plus"></i></a>
                                                    @if(!is_null($order) && $order->status == 11)
                                                        <a href="{{asset($order->scan)}}" target="_blank" class="btn btn-xs btn-primary"><i class="mdi mdi-account-search"></i></a>
                                                        <a href="/deactivation/{{ $item->id }}" target="_blank" class="btn btn-xs btn-danger"><i class="mdi mdi-account-remove"></i></a>
                                                    @endif
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
                                            @if(Gate::allows('admin_actions_user'))
                                            <td class="actions">
                                                @if(Gate::allows('admin_user_processing'))
                                                <a href="/user/{{ $item->id }}/processing" target="_blank" class="btn  btn-xs btn-info"  title="Финансы"><i class="mdi mdi-cash-multiple"></i></a>
                                                @endif
                                                @if(Gate::allows('admin_user_program'))
                                                <a href="/user/{{ $item->id }}/program" target="_blank" class="btn  btn-xs btn-success"  title="Пакет, статус, офис"><i class="mdi mdi-account-settings-variant"></i></a>
                                                @endif
                                                @if(Gate::allows('admin_review_add_user'))
                                                <a href="{{ route('admin_review_add', [ 'id' => $item->id ]) }}" target="_blank" class="btn btn-xs btn-warning"  title="Добавить отзыв"><i class="mdi mdi-star"></i></a>
                                                @endif
                                                @if(Gate::allows('admin_transfer_user'))
                                                <a href="/user/{{ $item->id }}/transfer" target="_blank" class="btn  btn-xs btn-warning"  title="Перевод"><i class="mdi mdi-sitemap"></i></a>
                                                @endif
                                                @if(Gate::allows('admin_go_under_user'))
                                                <a href="/user/{{ $item->id }}" target="_blank" class="btn  btn-xs btn-info"   title="Зайти под"><i class="mdi mdi-eye"></i></a>
                                                @endif
                                                @if(Gate::allows('admin_user_edit'))
                                                <a href="/user/{{ $item->id }}/edit" class="btn  btn-xs btn-success"  title="Изменить"><i class="mdi mdi-grease-pencil" ></i></a>
                                                @endif
                                                @if(Gate::allows('admin_user_add_bonus'))
                                                <a href="/user/{{ $item->id }}/add_bonus/" class="btn  btn-xs btn-success"  title="Добавить баланс"><svg style="width:10px;height:auto" viewBox="0 4 24 16"><path fill="currentColor" d="M3,6H21V18H3V6M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9M7,8A2,2 0 0,1 5,10V14A2,2 0 0,1 7,16H17A2,2 0 0,1 19,14V10A2,2 0 0,1 17,8H7Z" /></svg></a>
                                                @endif
                                                @if(Gate::allows('admin_user_destroy'))
                                                <form action="{{url('user', [$item->id])}}" method="POST">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn  btn-xs btn-danger" onclick="return deleteAlert();" title="Удалить"><i class="mdi mdi-delete"></i></button>
                                                </form>
                                                @endif
                                            </td>
                                            @endif
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

