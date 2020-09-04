<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block p-b-0">
                <h4 class="card-title">Доступные операции</h4>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Вывод наличными</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Вывод на карту</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Вывод на Payeer</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile2" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down">Вывод на Indigo</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#checkingAccount" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Вывод на ИП, ТОО, ОО, ЧП</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#messages2" role="tab"><span class="hidden-sm-up"><i class="ti-email"></i></span> <span class="hidden-xs-down">Переводы</span>
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active   p-20" id="home2" role="tabpanel">
                        <form {{--action="/processing"--}} action="/request" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="hidden" value="1" name="program_id">
                                        <input type="text"  name="sum" class="form-control" placeholder="Выводимая сумма" max="{{ $balance }}" required>
                                        <input type="text"  name="login" class="form-control" placeholder="Номер карты" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Вывести</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>
                    <div class="tab-pane  p-20" id="profile2" role="tabpanel">
                        <form {{--action="/processing"--}} action="/request" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="hidden" value="1" name="program_id">
                                        <input type="text"  name="sum" class="form-control" placeholder="Выводимая сумма" max="{{ $balance }}" required>
                                        <input type="text"  name="login" class="form-control" placeholder="Логин в системе" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Вывести</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>
                    <div class="tab-pane  p-20" id="checkingAccount" role="tabpanel">
                        <form {{--action="/processing"--}} action="/request" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="hidden" value="1" name="program_id">
                                        <input type="hidden" value="checking-account" name="withdrawal_method">
                                        <input type="text"  name="sum" class="form-control" placeholder="Выводимая сумма" max="{{ $balance }}" required>
                                        <input type="text"  name="login" class="form-control" placeholder="Номер расчетного счёта" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Вывести</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>
                    <div class="tab-pane p-20" id="messages2" role="tabpanel">
                        <div class="alert alert-warning"> <i class="mdi mdi-cash-multiple"></i> Переводы не доступны.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                        </div>
                        {{--<form action="/transfer" method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="hidden" value="1" name="program_id">
                                        <input type="text"  name="sum" class="form-control" placeholder="Переводимая сумма" max="{{ $balance }}" required>
                                        <input type="text"  name="transfer_user_id" class="form-control" placeholder="Почта абонента" required>
                                        <span class="input-group-btn">
                                    <button class="btn btn-info" type="submit">Перевести</button>
                                </span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
