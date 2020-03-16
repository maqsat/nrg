@extends('layouts.profile')

@section('in_content')
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            <div class="row page-titles">
                <div class="col-md-12 col-12 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Иерархия</h3>
                </div>
                <div class="col-md-6 col-4 align-self-center">
                    <ul class="tree vertical">
                        <li>
                           <div>{{ Auth::user()->name }}</div>
                           {!! $tree !!}
                        </li>
                    </ul>
                </div>
            </div>
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
<link href="/css/tree.css" rel="stylesheet">

<style>
    body{
        overflow-x: scroll !important;
    }
</style>
@endpush
