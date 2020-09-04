@extends('layouts.template')
@push('styles')
@endpush
@section('content')
    <section id="wrapper" class="login-register"  style="background-image:url(/monster_admin/assets/images/background/login-register.jpg);">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card">
                    <div class="card-block wizard-content">
                        <a href="/" class="text-center db">
                            <img src="/website/img/logo/logo.png" alt="Home" class="login_logo" /><br/>
                        </a>

                        <div class="row">
                            <div class="col-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="/register" method="post">
                                    @csrf
                                    <input type="hidden"  name="type" value="usual">
                                    <section>
                                        <div class="row">
                                            <div class="col-md-5  offset-1">
                                                <div class="form-group">
                                                    <label>ФИО :</label>
                                                    <input type="text" class="form-control required" id="name" name="name">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Телефон :</label>
                                                    <input type="number" class="form-control required" id="number" name="number">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email :</label>
                                                    <input type="email" class="form-control required"  id="email" name="email">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Пароль :</label>
                                                    <input type="password" class="form-control required" id="password" name="password">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Повторите пароль :</label>
                                                    <input type="password" class="form-control required" id="password_confirmation" name="password_confirmation">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-5  offset-1">
                                                <div class="form-group">
                                                    <label>Страна :</label>
                                                    <select class="custom-select form-control  required" id="country_id" name="country_id">
                                                        <option></option>
                                                        @foreach(\App\Models\Country::all() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('country_id') == $item->id) selected @endif>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Город :</label>
                                                    <select class="custom-select form-control required" id="city_id" name="city_id">
                                                        <option></option>
                                                        @foreach(\App\Models\City::all() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('city_id') == $item->id) selected @endif>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="submit"  value="Зарегистрироватся">
                                                </div>
                                            </div>
                                        </div>
                                    </section>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts')
    <script>
        $('#slimtest1').slimScroll({
            height: '200px'
        });
        $('#slimtest2').slimScroll({
            height: '200px'
        });
    </script>
@endpush