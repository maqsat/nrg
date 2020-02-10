@if($activation)
    <form  {{--action="/processing"--}} method="post">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-lg-12">
                <div class="input-group">
                    <input type="number"  name="sum" class="form-control" placeholder="Выводимая сумма" max="{{ $balance*385 }}" min="500" required>
                    <input type="text"  name="card" class="form-control" placeholder="Номер карты" required>
                    <span class="input-group-btn">
                    <button class="btn btn-info" type="submit">Вывести</button>
                </span>
                </div>
            </div>
        </div>
        <br>
    </form>
@else
    <h3>Чтобы вывести средства вы должны Активировать аккаунт, <a href="/activation-store">перейдите что бы проверить историю Активации</a> </h3>
@endif

{{--
<form action="/transfer" method="post">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-lg-12">
            <div class="input-group">
                <input type="number"  name="sum" class="form-control" placeholder="Переводимая сумма" max="{{ $balance }}" min="5000" required>
                <input type="number"  name="transfer_user_id" class="form-control" placeholder="ID Абонента" required>
                <span class="input-group-btn">
                    <button class="btn btn-info" type="submit">Перевести</button>
                </span>
            </div>
        </div>
    </div>
    <br>
</form>--}}
