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
                    <h3 class="text-themecolor m-b-0 m-t-0">Уведомления</h3>
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
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">

                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Тип</th>
                                        <th>Пользователь</th>
                                        <th>Сообщение</th>
                                        <th>Дата</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($all as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->id }}</td>
                                            @if($item->type == 'admin_register_user')
                                                <td><span class="label label-rouded label-success">регистрация пользователя</span></td>
                                            @elseif($item->type == 'admin_activated_user')
                                                <td><span class="label label-rouded label-success">активация пользователя</span></td>
                                            @elseif($item->type == 'admin_upgrade_user')
                                                <td><span class="label label-rouded label-success">апгрейд пользователя</span></td>
                                            @elseif($item->type == 'admin_buy_user')
                                                <td><span class="label label-rouded label-success">покупка пользователя</span></td>
                                            @endif
                                            <td class="txt-oflo">{{ \App\User::find($item->user_id)->name }}</td>
                                            <td class="txt-oflo">{{ $item->message }}</td>
                                            <td class="txt-oflo">{{ $item->created_at }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{ $all->links() }}
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
