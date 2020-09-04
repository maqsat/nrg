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
                    <h3 class="text-themecolor m-b-0 m-t-0">{{ $message }}</h3>
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
                        <div class="card-block prepare">
                            <form method="post" action="https://payeer.com/merchant/">
                                <input type="hidden" name="m_shop" value="<?=$m_shop?>">
                                <input type="hidden" name="m_orderid" value="<?=$m_orderid?>">
                                <input type="hidden" name="m_amount" value="<?=$m_amount?>">
                                <input type="hidden" name="m_curr" value="<?=$m_curr?>">
                                <input type="hidden" name="m_desc" value="<?=$m_desc?>">
                                <input type="hidden" name="m_sign" value="<?=$sign?>">
                                <input type="hidden" name="m_process" value="send" />
                                <button type="submit" class="btn btn-lg btn-info waves-effect waves-light">Перейти на PAYEER</button>
                            </form>
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

@push('scripts')
    @if (session('status'))
        <script>
            $.toast({
                heading: 'Результат действии',
                text: '{{ session('status') }}',
                position: 'top-right',
                loaderBg:'#ffffff',
                icon: 'info',
                textColor: 'white',
                hideAfter: 3000,
                stack: 6
            });
        </script>
    @endif
@endpush

