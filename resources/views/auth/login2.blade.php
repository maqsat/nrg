@extends('layouts.template')

@section('content')
    <section id="wrapper" class="login-register"  style="background-image:url(/monster_admin/assets/images/background/login-register.jpg);">
        <div class="login-box card">
            <div class="card-block">
                <form method="POST" action="{{ route('login') }}" class="form-horizontal form-material">
                    @csrf
                    <a href="javascript:void(0)" class="text-center db">
                        <img src="/website/img/logo/logo.png" alt="Home" class="login_logo" /><br/>
                    </a>

                    <div class="form-group m-t-40">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" name="email" required="" placeholder="{{ __('app.sign in login') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="password" required="" placeholder="{{ __('app.password') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary pull-left p-t-0">
                                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label for="checkbox-signup"> {{ __('app.remember me') }} </label>
                            </div>
                            <a href="{{ route('password.request') }}" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> {{ __('app.forgot pwd?') }}</a> </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">{{ __('app.log in') }}</button>
                        </div>
                    </div>
                    <div class="form-group m-b-0">
                        <div class="col-sm-12 text-center">
                            <p>{{ __('app.Dont have an account?') }} <a href="/register" class="text-primary m-l-5"><b>{{ __('app.Sign Up in Login') }}</b></a></p>
                        </div>
                        <div class="col-sm-12 text-center">
                            <p>Зарегистрироватся в интернет магазине <a href="/userregister" class="text-primary m-l-5"><b>{{ __('app.Sign Up in Login') }}</b></a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    @if ($errors->any())
        <script src="/monster_admin/main/js/toastr.js"></script>
        <script src="/monster_admin/assets/plugins/toast-master/js/jquery.toast.js"></script>
        @foreach ($errors->all() as $error)
            <script>
                $.toast({
                    heading: '{{ __('app.errors in login') }}',
                    text: '{{ __($error) }}',
                    position: 'top-right',
                    loaderBg:'#ffffff',
                    icon: 'error',
                    hideAfter: 30000,
                    stack: 6
                });
            </script>
        @endforeach
   @endif
@endpush

@push('styles')
    <link href="/monster_admin/assets/plugins/toast-master/css/jquery.toast.css" rel="stylesheet">
@endpush
