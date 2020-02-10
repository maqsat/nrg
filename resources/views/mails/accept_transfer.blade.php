@component('mail::message')


- Вы запустили трансфер на сумму - <b>{{ $sum }}  тг</b>
- Получатель  - <b>{{ $name }}</b>


@component('mail::two-button', ['url_one' => $accept_link, 'color_one' => 'success', 'url_two' => $cancel_link, 'color_two' => 'error'])
    Подтвердить
@endcomponent

C уважением,<br>
{{ config('app.name') }}
@endcomponent

