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
                    <h3 class="text-themecolor m-b-0 m-t-0">Тип оплата  - "Скан квитанции", Сумма оплаты - ${{ $cost }}</h3>
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
                            <form action="/pay-processing/{{ $order->id }}"  method="post"   enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="type" value="manual">
                                <h4 class="card-title"> Квитанция об оплате</h4>
                                <label for="input-file-now">Загрузить изображение или перетащите в эту область</label>
                                <input type="file" id="input-file-now" class="dropify" name="scan" />
                                <button type="submit" class="btn btn-success waves-effect waves-light m-t-20">Отправить на проверку</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title">Реквизиты и счета</h4>
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-bookmark  ribbon-warning"> Реквизиты</div>
                        <p class="ribbon-content"> ТОО «ЭнерджиМакс»</p>
                        <p class="ribbon-content"> БИН 191140005599</p>
                        <p class="ribbon-content"> Адрес: 014000, РК, г.Нур-Султан, Баконыр р-н, пр.Абая 45/1</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-bookmark  ribbon-danger">Расчетный счет в АО «Kaspi Bank»</div>
                        <p class="ribbon-content"> БИК: CASPKZKA</p>
                        <p class="ribbon-content"> Счет ТГ: KZ26722S000002852532</p>
                        <p class="ribbon-content"> Счет USD: KZ21722S000002852719</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ribbon-wrapper card">
                        <div class="ribbon ribbon-bookmark  ribbon-info"> Расчетный счет в «ForteBank»</div>
                        <p class="ribbon-content"> БИК: IRTYKZKA</p>
                        <p class="ribbon-content"> Счет ТГ: KZ0396503F0009281576</p>
                        <p class="ribbon-content"> Счет USD: KZ7296503F0009281595</p>
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

@push('scripts')
<link rel="stylesheet" href="/monster_admin/assets/plugins/dropify/dist/css/dropify.min.css">
@endpush

@push('scripts')
<!-- jQuery file upload -->
<script src="/monster_admin/assets/plugins/dropify/dist/js/dropify.min.js"></script>
<script>
    $(document).ready(function() {
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>
@endpush
