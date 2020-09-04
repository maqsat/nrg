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
                    <h3 class="text-themecolor m-b-0 m-t-0">Лидеры</h3>
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
                            <form  action="/progress" method="get">
                                {{ csrf_field() }}
                                <div class="row">
                                    {{--<div class="col-lg-12">
                                        <div class="input-group">
                                            <input type="date"  name="from"  class="form-control" required>
                                            <input type="date"  name="to" class="form-control" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Фильтровать</button>
                                        </span>
                                        </div>
                                    </div>--}}
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="date" name="from" class="form-control" value="{{ old('from',$from) }}" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="date" name="to"  class="form-control"  value="{{ old('to',$to) }}" placeholder="dd/mm/yyyy">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                           <span class="input-group-btn">
                                            <button class="btn btn-info progress" type="submit">Фильтровать</button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            </form>
                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Пользователь</th>
                                        <th>Количество приглашений</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($list as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->inviter_id }}</td>
                                            <td><span class="text-success">{{ \App\User::find($item->inviter_id)->name }}</span></td>
                                            <td><span class="text-success">{{ $item->count }}</span></td>
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
