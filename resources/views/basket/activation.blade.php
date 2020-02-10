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
                    <h3 class="text-themecolor m-b-0 m-t-0">История активации</h3>
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
                            <div class="table-responsive">
                                <table class="table color-table success-table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Месяц</th>
                                        <th>Сумма покупки</th>
                                        <th>Сумма активации</th>
                                        <th>Статус активации</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data as $key => $item)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $item['month'] }}</td>
                                            <?php  ?>
                                            <td>{{ $item['cost'] }}$</td>
                                            <td>{{ $item['activation'] }}$</td>
                                            <td>
                                                @if($item['cost'] >= $item['activation'] )
                                                    <h5>Активирован<span class="pull-right">100%</span></h5>
                                                    <div class="progress ">
                                                        <div class="progress-bar bg-success wow animated progress-animated" style="width: 100%; height:6px;" role="progressbar"> <span class="sr-only"></span> </div>
                                                    </div>
                                                @else
                                                    <?php $percentage = round($item['cost']*100/$item['activation']) ?>
                                                    <h5>Не активирован<span class="pull-right">{{ $percentage }}%</span> </h5>
                                                    <div class="progress ">
                                                        <div class="progress-bar bg-danger wow animated progress-animated" style="width: {{ $percentage }}%; height:6px;" role="progressbar"> <span class="sr-only"></span> </div>
                                                    </div>
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

@push('scripts')

@endpush

