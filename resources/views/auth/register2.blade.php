@extends('layouts.template')
@push('styles')
@endpush
@section('content')
    <section id="wrapper" class="login-register"  style="background-image:url(/monster_admin/assets/images/background/login-register.jpg);">
        <div class="row">
            <div class="col-10 offset-1">
                <div class="card">
                    <div class="card-block wizard-content">
                        <a href="javascript:void(0)" class="text-center db">
                            <img src="/website/img/logo/logo.png" alt="Home" class="login_logo" /><br/>
                        </a>

                        <div class="row">
                            <div class="col-12">
                                <form  class="validation-wizard wizard-circle">
                                    <!-- Step 1 -->
                                    <h6>О дистрибьюторе</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-md-2">
                                                <input type="hidden" name="program_id" id="program_id" value="1">
                                                <div class="form-group">
                                                    <label for="inviter_id">Для обработки вашего заказа, введите, пожалуйста, ID спонсора(дистрибьютора) :</label>
                                                    <input type="number" class="form-control required" id="inviter_id" name="inviter_id" value="{{ app('request')->input('inviter_id') }}">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sponsor_id">Для обработки вашего заказа, введите, пожалуйста, ID наставника(уточните у спонсора):</label>
                                                    <input type="number" class="form-control required"  id="sponsor_id" name="sponsor_id" value="{{ app('request')->input('sponsor_id') }}">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="position">Позиция :</label>
                                                    <select class="custom-select form-control required" id="position" name="position">
                                                        <option value="1">Первая ветка</option>
                                                        <option value="2">Вторая ветка</option>
                                                        <option value="3">Третья ветка</option>
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 2 -->
                                    <h6>Персональный данные</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-5  offset-1">
                                                <div class="form-group">
                                                    <label for="name">ФИО :</label>
                                                    <input type="text" class="form-control required" id="name" name="name">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="number">Телефон :</label>
                                                    <input type="number" class="form-control required" id="number" name="number">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email :</label>
                                                    <input type="email" class="form-control required"  id="email" name="email">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="iin">Номер документа(ИИН) :</label>
                                                    <input type="number" class="form-control required" id="iin" name="iin">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="birthday">День рождение :</label>
                                                    <input type="date" class="form-control required" id="birthday" name="birthday">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password">Пароль :</label>
                                                    <input type="password" class="form-control required" id="password" name="password">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="password_confirmation">Повторите пароль :</label>
                                                    <input type="password" class="form-control required" id="password_confirmation" name="password_confirmation">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 3 -->
                                    <h6>Договор</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-2">
                                                <h3 class="box-title">ПОЛЬЗОВАТЕЛЬСКОЕ СОГЛАШЕНИЕПОЛЬЗОВАТЕЛЬСКОЕ</h3>
                                                <hr class="m-t-0 m-b-20">
                                                <div id="slimtest1">
                                                    @include('auth.contract_one')
                                                </div>
                                                <hr class="m-t-0 m-b-20">
                                                <h3 class="box-title">ПУБЛИЧНЫЙ ДОГОВОР ПОСТАВКИ ПРОДУКЦИИ</h3>
                                                <hr class="m-t-0 m-b-20">
                                                <div id="slimtest2">
                                                    @include('auth.contract_two')
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <div class="checkbox checkbox-primary pull-left p-t-30">
                                                            <input type="checkbox" id="terms" name="terms" class="required" checked>
                                                            <label for="checkbox-signup">  Я ознакомился с условиями оферты и принимаю договор оферты </label>
                                                            <div class="error-message"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 4 -->
                                    <h6>Доставка</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-2">
                                                <div class="form-group">
                                                    <label for="country_id">Страна :</label>
                                                    <select class="custom-select form-control  required" id="country_id" name="country_id">
                                                        <option></option>
                                                        @foreach(\App\Models\Country::all() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('country_id') == $item->id) selected @endif>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="city_id">Город :</label>
                                                    <select class="custom-select form-control required" id="city_id" name="city_id">
                                                        <option></option>
                                                        @foreach(\App\Models\City::all() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('city_id') == $item->id) selected @endif>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Улица, номер дома, номер квартиры :</label>
                                                    <input type="text" class="form-control required" id="address" name="address">
                                                    <div class="error-message"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="post_index">Почтовой индекс  :</label>
                                                    <input type="number" class="form-control required" id="post_index" name="post_index">
                                                    <div class="error-message"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                    <!-- Step 5 -->
                                    <h6>Оплата</h6>
                                    <section>
                                        <div class="row">
                                            <div class="col-md-8  offset-2">
                                                <div class="form-group">
                                                    <label for="package_id">Выберите пакет:</label>
                                                    <select class="custom-select form-control required" id="package_id" name="package_id">
                                                        <option></option>
                                                        @foreach(\App\Models\Package::where('status',1)->get() as $item)
                                                            <option value="{{ $item->id }}"  @if(old('package_id') == $item->id) selected @endif>{{ $item->title }}({{$item->qv}}/{{$item->cv}}/{{$item->bv}}) - {{ $item->cost }} тг/ {{ intval($item->cost)/385 }} $ </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="error-message"></div>
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
        $(".tab-wizard").steps({
            headerTag: "h6"
            , bodyTag: "section"
            , transitionEffect: "fade"
            , titleTemplate: '<span class="step">#index#</span> #title#'
            , labels: {
                finish: "Submit"
            }
            , onFinished: function (event, currentIndex) {
                swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

            }
        });

        function registerStep1 (currentIndex) {

        }

        var form = $(".validation-wizard").show();

        $(".validation-wizard").steps({
            headerTag: "h6",
            bodyTag: "section",
            transitionEffect: "fade",
            titleTemplate: '<span class="step">#index#</span> #title#',
            labels: {
                finish: "Submit"
            },
            onStepChanging: function (event, currentIndex, newIndex) {
                $('.error-message').html('');
                $('input').removeClass('error');

                if(currentIndex == 0){

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);

                    var params = 'program_id='+document.getElementById('program_id').value+'&inviter_id='+document.getElementById('inviter_id').value+'&sponsor_id='+document.getElementById('sponsor_id').value+'&position='+document.getElementById('position').value+'&step='+currentIndex;
                    console.log(params);
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }
                }
                if(currentIndex == 1){
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);

                    var params = 'name='+document.getElementById('name').value+'&number='+document.getElementById('number').value+'&email='+document.getElementById('email').value+'&iin='+document.getElementById('iin').value+'&birthday='+document.getElementById('birthday').value+'&password='+document.getElementById('password').value+'&password_confirmation='+document.getElementById('password_confirmation').value+'&step='+currentIndex;



                    console.log(params);
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }

                }
                if(currentIndex == 2){
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);
                    var params = 'terms='+document.getElementById('terms').value+'&step='+currentIndex;
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }
                }
                if(currentIndex == 3){

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);
                    var params = 'post_index='+document.getElementById('post_index').value+'&country_id='+document.getElementById('country_id').value+'&city_id='+document.getElementById('city_id').value+'&address='+document.getElementById('address').value+'&step='+currentIndex;
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }
                }
                if(currentIndex == 4){
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', '/register-validate', false);
                    var params = 'package_id='+document.getElementById('package_id').value+'&step='+currentIndex;
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    xhr.send(params);
                    if (xhr.status != 200) {
                        alert( xhr.status + ': ' + xhr.statusText );
                    }
                    else {
                        var result=JSON.parse(xhr.responseText);
                        console.log( JSON.parse(xhr.responseText) );
                        if(result["status"] == false){
                            for (var key in result.error_list) {
                                $('#' + key).addClass('error');
                                $('#' + key).closest('.form-group').find('.error-message').html('<label id="inviter_id-error" class="text-danger" for="inviter_id">' + result.error_list[key][0] + '</label>');
                            }
                            return false;
                        }
                        return true;
                    }
                }
            },
            onFinishing: function (event, currentIndex) {
                $.ajax({
                    type: "POST",
                    url: "/register",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        program_id: document.getElementById('program_id').value,
                        inviter_id: document.getElementById('inviter_id').value,
                        sponsor_id: document.getElementById('sponsor_id').value,
                        position: document.getElementById('position').value,
                        name: document.getElementById('name').value,
                        number: document.getElementById('number').value,
                        email: document.getElementById('email').value,
                        iin: document.getElementById('iin').value,
                        birthday: document.getElementById('birthday').value,
                        password: document.getElementById('password').value,
                        password_confirmation: document.getElementById('password_confirmation').value,
                        terms: document.getElementById('terms').value,
                        country_id: document.getElementById('country_id').value,
                        city_id: document.getElementById('city_id').value,
                        address: document.getElementById('address').value,
                        post_index: document.getElementById('post_index').value,
                        package_id: document.getElementById('package_id').value
                    },
                    success: function(){
                        window.location.href = "/home";
                    },
                    error: function (request, status, error) {
                        alert('Есть ошибка в регистрационнных данных');
                    }
                });

                return form.validate().settings.ignore = ":disabled", form.valid()
            }
            , onFinished: function (event, currentIndex) {
                //swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
            }
        }), $(".validation-wizard").validate({
            ignore: "input[type=hidden]"
            , errorClass: "text-danger"
            , successClass: "text-success"
            , highlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            }
            , unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass)
            }
            , errorPlacement: function (error, element) {
                error.insertAfter(element)
            }
            , rules: {
                email: {
                    email: !0
                }
            }
        })
    </script>
    <script>
        $('#slimtest1').slimScroll({
            height: '200px'
        });
        $('#slimtest2').slimScroll({
            height: '200px'
        });
    </script>
@endpush