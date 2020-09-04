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
                <div class="col-lg-4 col-md-4">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-info">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0 text-info">{{ $in }}</h3>
                                <h5 class="text-muted m-b-0">Пополнение</h5></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-4 col-md-4">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-success">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0 text-success">{{ $out }}</h3>
                                <h5 class="text-muted m-b-0">Выведено</h5></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-4 col-md-4">
                    <div class="card">
                        <div class="d-flex flex-row">
                            <div class="p-10 bg-inverse">
                                <h3 class="text-white box m-b-0"><i class="ti-wallet"></i></h3></div>
                            <div class="align-self-center m-l-20">
                                <h3 class="m-b-0">{{ $all }}</h3>
                                <h5 class="text-muted m-b-0">Общий оборот</h5></div>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>

            {{--<div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">
                            <h4 class="card-title">Faqs with accordion</h4>
                            <h6 class="card-subtitle">add class<code>minimal-faq</code> to accordion for clean faq</h6>
                            <div id="accordion2" role="tablist" class="minimal-faq" aria-multiselectable="true">
                                <div class="card m-b-0">
                                    <div class="card-header" role="tab" id="headingOne11">
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne11" aria-expanded="true" aria-controls="collapseOne11">
                                                Q1. How can i purchase this admin ?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseOne11" class="collapse show" role="tabpanel" aria-labelledby="headingOne11">
                                        <div class="card-block">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="card m-b-0">
                                    <div class="card-header" role="tab" id="headingTwo22">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo22" aria-expanded="false" aria-controls="collapseTwo22">
                                                Q2. How to modify Navigation?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo22" class="collapse" role="tabpanel" aria-labelledby="headingTwo22">
                                        <div class="card-block">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="card m-b-0">
                                    <div class="card-header" role="tab" id="headingThree33">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree33" aria-expanded="false" aria-controls="collapseThree33">
                                                Q3. How to get yearly Support?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseThree33" class="collapse" role="tabpanel" aria-labelledby="headingThree33">
                                        <div class="card-block">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="card m-b-0">
                                    <div class="card-header" role="tab" id="headingOne111">
                                        <h5 class="mb-0">
                                            <a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne111" aria-expanded="true" aria-controls="collapseOne111">
                                                Q4. How can i purchase this admin ?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseOne111" class="collapse" role="tabpanel" aria-labelledby="headingOne111">
                                        <div class="card-block">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="card m-b-0">
                                    <div class="card-header" role="tab" id="headingTwo222">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo222" aria-expanded="false" aria-controls="collapseTwo222">
                                                Q5. How to modify Navigation?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseTwo222" class="collapse" role="tabpanel" aria-labelledby="headingTwo222">
                                        <div class="card-block">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" role="tab" id="headingThree333">
                                        <h5 class="mb-0">
                                            <a class="collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree333" aria-expanded="false" aria-controls="collapseThree333">
                                                Q6. How to get yearly Support?
                                            </a>
                                        </h5>
                                    </div>
                                    <div id="collapseThree333" class="collapse" role="tabpanel" aria-labelledby="headingThree333">
                                        <div class="card-block">
                                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="table-responsive">
                                <table id="demo-foo-addrow" class="table table-hover no-wrap contact-list" data-page-size="10">
                                    <thead>
                                    <tr>
                                        <th>ID #</th>
                                        <th>Статус</th>
                                        @if(isset($_GET['status']) && $_GET['status'] == 'request')<th>Ответ</th>@endif
                                        <th>Сумма</th>
                                        <th>От кого</th>
                                        <th>Отправитель</th>
                                        <th>Номер карты</th>
                                        <th>Метод вывода</th>
                                        <th>Дата</th>
                                        <th>Действие</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($list as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->id }}</td>
                                            <td>
                                                @include('processing.processing-title')
                                            </td>
                                            @if(isset($_GET['status'])  && $_GET['status'] == 'request')
                                                <td  class="actions">
                                                    <form action="/processing/{{ $item->id }}" method="POST" style="display: inline-block;">
                                                        {{ csrf_field() }}
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="out">
                                                        <button class="btn btn-success" type="submit"><i class="mdi mdi-check"></i></button>
                                                    </form>
                                                    <form action="/processing/{{ $item->id }}" method="POST"  style="display: inline-block;">
                                                        {{ csrf_field() }}
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="cancel">
                                                        <button class="btn btn-danger" type="submit"><i class="mdi mdi-close"></i></button>
                                                    </form>
                                                </td>
                                            @endif
                                            <td><span class="text-success">{{ round($item->sum,2) }} $</span></td>
                                            <?php
                                                $in_user = \App\User::find($item->in_user);
                                                $user_id = \App\User::find($item->user_id);
                                            ?>
                                            <td class="txt-oflo">@if(!is_null($user_id)) {{ $user_id->name }} @else Не найден @endif </td>
                                            <td class="txt-oflo">@if(!is_null($in_user)) {{ $in_user->name }} @else {{ $item->in_user }} @endif @if($item->status == 'matching_bonus') <i>{{ $item->matching_line }} линия</i> @endif  </td>
                                            <td>{{ $item->card_number }}</td>
                                            <td>@if($item->withdrawal_method === 'checking-account') Расчётный счёт @endif</td>
                                            <td class="txt-oflo">{{ $item->created_at }}</td>
                                            <td class="actions">
                                                <a href="/user/{{ $user_id->id }}/processing" target="_blank" class="btn  btn-xs btn-success"  title="Финансы"><i class="mdi mdi-cash-multiple"></i></a>
                                                <a href="/user/{{ $user_id->id }}" target="_blank" class="btn  btn-xs btn-info"   title="Зайти под"><i class="mdi mdi-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if(isset($_GET['status']))
                                {{ $list->appends(['status' => $_GET['status']])->links() }}
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

