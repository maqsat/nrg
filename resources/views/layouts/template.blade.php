<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="/landingpage/custom_images/favicon.png">

    <title>{{ env('APP_NAME') }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="/monster_admin/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/wizard/steps.css" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link href="/monster_admin/horizontal/css/style.css" rel="stylesheet">
    <link href="/css/custom.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/chartist-js/dist/chartist-init.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
    <link href="/monster_admin/assets/plugins/css-chart/css-chart.css" rel="stylesheet">

    @stack('styles')
    <!-- You can change the theme colors from here -->
    <link href="/monster_admin/horizontal/css/colors/green.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="@yield('body-class')">
<!-- ============================================================== -->
<!-- Preloader - style you can find in spinners.css -->
<!-- ============================================================== -->
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
</div>
<!-- ============================================================== -->
<!-- Main wrapper - style you can find in pages.scss -->
<!-- ============================================================== -->
<div class="wrapper">

    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->

    @yield('content')
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="/monster_admin/assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="/monster_admin/assets/plugins/bootstrap/js/tether.min.js"></script>
<script src="/monster_admin/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="/monster_admin/horizontal/js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="/monster_admin/horizontal/js/waves.js"></script>
<!--Menu sidebar -->
<script src="/monster_admin/horizontal/js/sidebarmenu.js"></script>
<!--stickey kit -->
<script src="/monster_admin/assets/plugins/sticky-kit-master/dist/sticky-kit.min.js"></script>
<!--Custom JavaScript -->
<script src="/monster_admin/horizontal/js/custom.min.js"></script>
<script src="/monster_admin/horizontal/js/jasny-bootstrap.js"></script>
<script src="/monster_admin/assets/plugins/wizard/jquery.steps.min.js"></script>
<script src="/monster_admin/assets/plugins/wizard/jquery.validate.min.js"></script>
<script src="/monster_admin/assets/plugins/wizard/steps.js"></script>
<!-- ============================================================== -->
<script src="/monster_admin/horizontal/js/toastr.js"></script>
<script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="/monster_admin/horizontal/js/jquery.slimscroll.js"></script>
<script src="/monster_admin/assets/plugins/echarts/echarts-all.js"></script>
<script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
@stack('scripts')
<!-- Style switcher -->
<!-- ============================================================== -->
<script src="/monster_admin/assets/plugins/styleswitcher/jQuery.style.switcher.js"></script>
</body>
</html>
