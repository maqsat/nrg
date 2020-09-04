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
                    <h3 class="text-themecolor m-b-0 m-t-0">Моя команда</h3>
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
            <form method="get" class="form-horizontal user_create">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-12">

                        @if(isset($_GET['upgrade']))
                            <input type="hidden" value="1" name="upgrade">
                        @endif

                        @if(isset($_GET['move']))
                            <input type="hidden" value="1" name="move">
                        @endif

                        <label class="m-t-10">Дата фильтрации:</label>
                        <div class="input-group">
                            <input type="date" name="date" class="form-control form-control-line" required>
                        </div>

                        <label  class="m-t-10" for="position">Статус  фильтрации:</label>
                        <div class="input-group">
                            <select class="custom-select form-control required" id="status_id" name="status_id" required>
                                <option>Не указан</option>
                                <option value="1">Участник</option>
                                <option value="2">Партнер</option>
                                <option value="3">Менеджер</option>
                                <option value="4">Бронза</option>
                                <option value="5">Серебро</option>
                                <option value="6">Золото</option>
                                <option value="7">Платина</option>
                                <option value="8">Бриллиант</option>
                                <option value="9">Бр. менеджер</option>
                                <option value="10">Бр. директор</option>
                            </select>
                        </div>
                    </div>
                </div>
                <hr>
                <span class="input-group-btn">
                    <button class="btn btn-info" type="submit">Отправить</button>
                </span>
            </form>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">

                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>ФИО</th>
                                        <th>Статус</th>
                                        <th>Пакет</th>
                                        <th>Номер</th>
                                        <th>Почта</th>
                                        @if(Auth::user()->admin == 1)
                                        <th>Структура</th>
                                        @endif
                                        <th>Дат/рег</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->user_id }}</td>
                                            <td><span class="text-success">{{ \App\User::find($item->user_id)->name }}</span></td>
                                            <td class="txt-oflo">{{ \App\Models\Status::find($item->status_id)->title }}</td>
                                            <td class="txt-oflo">@if($item->package_id != 0)  {{ \App\Models\Package::find($item->package_id)->title }} @else Без пакета @endif</td>
                                            <td><span class="text-success">{{ \App\User::find($item->user_id)->number }}</span></td>
                                            <td><span class="text-success">{{ \App\User::find($item->user_id)->email }}</span></td>

                                            @if(Auth::user()->admin == 1)
                                            <td>
                                                <?php
                                                    $user_changes = \DB::table('user_changes')->where('user_id',$item->user_id)->get();
                                                ?>

                                                @if(count($user_changes) > 0)
                                                    @foreach($user_changes as $change)
                                                        @if($change->type == 6)
                                                                Позиция изменена с @if($change->old == 1) <b>Левая ветка</b> @else <b>Правая ветка</b> @endif на @if($change->new == 1) <b>Левая ветка</b> @else <b>Правая ветка</b><br> @endif
                                                        @endif

                                                        @if($change->type == 5)
                                                            Наставник изменен с @if(!is_null(\App\User::find($change->old))) <b>{{ \App\User::find($change->old)->name }}</b> @endif на @if(!is_null(\App\User::find($change->new))) <b>{{ \App\User::find($change->new)->name }}</b><br> @endif
                                                        @endif

                                                        @if($change->type == 4)
                                                            Спонсор изменен с @if(!is_null(\App\User::find($change->old))) <b>{{ \App\User::find($change->old)->name }}</b> @endif на @if(!is_null(\App\User::find($change->new))) <b>{{ \App\User::find($change->new)->name }}</b><br> @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            @endif
                                            <td>
                                                @if(isset($_GET['upgrade']) or isset($_GET['move']))
                                                    {{ date('d-m-Y', strtotime($item->created_at)) }}
                                                @else
                                                    {{ date('d-m-Y', strtotime(\App\User::find($item->user_id)->created_at)) }}
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if(isset($_GET['own']))
                                {{ $list->appends(['own' => $_GET['own']])->links() }}
                            @elseif(isset($_GET['status_id']) and isset($_GET['date']) and isset($_GET['move']))
                                {{ $list->appends(['status_id' => $_GET['status_id']])->appends(['date' => $_GET['date']])->appends(['move' => $_GET['move']])->links() }}
                            @elseif(isset($_GET['status_id']) and isset($_GET['date']) and isset($_GET['upgrade']))
                                {{ $list->appends(['status_id' => $_GET['status_id']])->appends(['date' => $_GET['date']])->appends(['upgrade' => $_GET['upgrade']])->links() }}
                            @elseif(isset($_GET['upgrade']))
                                {{ $list->appends(['upgrade' => $_GET['upgrade']])->links() }}
                            @elseif(isset($_GET['move']))
                                {{ $list->appends(['move' => $_GET['move']])->links() }}
                            @else
                                {{ $list->links() }}
                            @endif
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
