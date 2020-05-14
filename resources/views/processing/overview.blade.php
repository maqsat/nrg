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
                    <h3 class="text-themecolor m-b-0 m-t-0">{{ __('app.processing') }}</h3>
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
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Регистрация</h4>
                            <div class="text-right">
                                <h1 class="font-light"><sup><i class="ti-arrow-up text-success"></i></sup> ${{ round($register) }}</h1>
                            </div>
                            <span class="text-success">80%</span>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 80%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Апгрейд</h4>
                            <div class="text-right">
                                <h1 class="font-light"><sup><i class="ti-arrow-up text-success"></i></sup> ${{ round($upgrade) }}</h1>
                            </div>
                            <span class="text-success">80%</span>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 80%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Интернет магазин</h4>
                            <div class="text-right">
                                <h1 class="font-light"><sup><i class="ti-arrow-up text-success"></i></sup> ${{ $shop }}</h1>
                            </div>
                            <span class="text-inverse">10%</span>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 10%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>

            <div class="row">
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Комиссионные</h4>
                            <div class="text-right">
                                <h1 class="font-light"><sup><i class="ti-arrow-down text-danger"></i></sup> ${{ $commission }}</h1>
                            </div>
                            <span class="text-danger">60%</span>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 60%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Выведено</h4>
                            <div class="text-right">
                                <h1 class="font-light"><sup><i class="ti-arrow-up text-danger"></i></sup> ${{ $out }}</h1>
                            </div>
                            <span class="text-danger">25%</span>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 60%; height: 6px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
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


@push('styles')
<style>
    .table td, .table th {
        padding: .25rem .15rem;
    }
</style>
<link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
@endpush


@push('scripts')
@if ($errors->has('login'))

    <script src="/monster_admin/main/js/toastr.js"></script>
    <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
    @foreach ($errors->all() as $error)
        <script>
            $.toast({
                heading: '{{ __('app.errors in login') }}',
                text: '{{ $errors->first('login') }}',
                position: 'top-left',
                loaderBg:'#ffffff',
                icon: 'warning',
                hideAfter: 30000,
                stack: 6
            });
        </script>
    @endforeach
@endif
@endpush

