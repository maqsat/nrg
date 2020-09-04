<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-block p-b-0">
                <h4 class="card-title">Доступные операции</h4>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs customtab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home2" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down">Вывод</span>
                        </a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active   p-20" id="home2" role="tabpanel">
                        <form action="/user/processing"  method="post">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="input-group">
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="text"  name="sum" class="form-control" placeholder="Выводимая сумма" max="{{ $balance }}" required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-info" type="submit">Вывести</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
