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
                    <h3 class="text-themecolor m-b-0 m-t-0">Офисы</h3>
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
                                        <th>Офисы</th>
                                        <th>Балы</th>
                                        <th>Сумма</th>
                                        <th>Лидер</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($data as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key+1 }}</td>
                                            <td><span class="text-success">{{ $item[0]->title }}</span></td>
                                            <td><span class="text-success">{{ $item[1] }} PV</span></td>
                                            <td class="text-center">${{ $item[1]*0.1*env('COURSE') }}</td>
                                            <?php $res =  \App\User::whereOfficeId($item[0]->id)->first(); ?>
                                            <td><span class="text-success">@if(!is_null($res)){{ $res->name  }}@else Нет @endif</span></td>
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
