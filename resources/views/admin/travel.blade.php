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
                    <h3 class="text-themecolor m-b-0 m-t-0">Happy Travel</h3>
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
                                        <th>Пользователь</th>
                                        <th>Статус</th>
                                        <th>Тип</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($not_cash_bonuses as $key => $item)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ \App\User::find($item->user_id)->name }}</td>
                                            <td>{{ \App\Models\Status::find($item->status_id)->title }}</td>
                                            <td>{{ $item->type }}</td>
                                            <td class="actions">
                                            @if($item->status == 0)
                                                <a href="/not_cash_bonuses/{{ $item->id }}/1" class="btn  btn-xs btn-success"><i class="mdi mdi-check"></i> Отправить</a>
                                                <a href="/not_cash_bonuses/{{ $item->id }}/0" class="btn  btn-xs btn-danger"><i class="mdi mdi-close"></i> Отменить</a>
                                            @else
                                                @if($item->status == 1)
                                                        <button class="btn  btn-xs btn-info"><i class="mdi mdi-close"></i> Отправлен</button>
                                                @else
                                                        <button class="btn  btn-xs btn-warning"><i class="mdi mdi-close"></i> Отменен</button>
                                                @endif
                                            @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
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
