
<header class="topbar">
    <nav class="navbar top-navbar navbar-toggleable-sm navbar-light">
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-header">
            <a class="navbar-brand" href="/">
                <!-- Logo icon --><b>
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="/website/img/logo/logo.png" alt="homepage" class="dark-logo" />
                    <!-- Light Logo icon -->
                    <img src="/nrg/logo_white.svg" alt="homepage" class="light-logo" />
                    </b>
                    <!--End Logo icon -->
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav mr-auto mt-md-0 ">
                <!-- This is  -->
                <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="icon-arrow-left-circle"></i></a> </li>
            </ul>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <ul class="navbar-nav my-lg-0">
                <li class="nav-item hidden-sm-down">
                    <form class="app-search">
                        <input type="text" class="form-control" placeholder="Search for...">
                        <a class="srh-btn"><i class="ti-search"></i></a>
                    </form>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="@auth/{{Auth::user()->photo}}@else''@endauth" alt="user" class="profile-pic" />
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="flag-icon flag-icon-ru"></i>
                    </a>
                    {{--<div class="dropdown-menu  dropdown-menu-right animated bounceInDown">
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-in"></i> India</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-fr"></i> French</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-cn"></i> China</a>
                        <a class="dropdown-item" href="#"><i class="flag-icon flag-icon-de"></i> Dutch</a>
                    </div>--}}
                </li>
            </ul>
        </div>
    </nav>
</header>
