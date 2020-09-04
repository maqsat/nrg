<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    /*
        1:Waiting – Ожидает завершения оплаты пользователем
        2:Expired – Истекло время ожидания оплаты (5 минут)
        3:Canceled – Пользователь нажал назад на странице оплаты для ввода данных карт
        4:Paid – Оплата завершена успешно
        5:Failed – Возникла ошибка при оплате (часто из за недостачи средств или запрета проведений интернет-транзакций банком пользователя)
        6:Confirmed – Был произведен перевод средств на транзитный счет клиента (*Клиент – пользователь (Ticketon) который использует сервис PayPost )
        7: Full Refund - Был произведён полный возврат денег на карту оплаты
        8: Partial Refund - Был произведён частичный возврат денег на карту оплаты
        9: это FULL_REFUND_ACCOUNT_STATUS, то есть деньги в колвир посадили, после чего сделали возврат на карту
        10: это PARTIAL_REFUND_ACCOUNT_STATUS, то же самое, но возврат частичный
        11: квитанция отправлено на проверку
        12: фейковая квитанция
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
