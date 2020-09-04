<?php

namespace app\controllers;

use app\jobs\NotificationJob;
use app\jobs\RecalcJob;
use app\models\Charges;
use app\models\Indigo;
use app\models\Order;
use app\models\PayBoxs;
use app\models\Payeer;
use app\models\Payments;
use app\models\Product;
use app\models\Robo;
use app\models\User;
use app\models\UserProfile;
use yii\web\Controller;
use yii\helpers\Url;
use Paybox\Pay\Facade as Paybox;
use yii\web\ForbiddenHttpException;

/**
 * PayController implements the CRUD actions for Works model.
 */
class PayController extends Controller
{
    public $curr_price = 0;

    public function beforeAction($action)
    {
        /*if ($action->id == 'wm' || $action->id == 'payeer') {
            $this->enableCsrfValidation = false;
        }*/
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

    public function initPay($curr = 0) {
        if (\yii::$app->user->isGuest) {
            throw new ForbiddenHttpException();
        }

        $repeat = \yii::$app->request->get('repeat');
        $upgrade = \yii::$app->request->get('upgrade');

        $reg = false;
        if (!\yii::$app->user->identity->userProfile->is_complete) {
            $reg = true;
            $repeat = false;
            $upgrade = false;
        }

        $product_id = \yii::$app->request->post('product_id');
        if ($repeat) {
            $product_id = \yii::$app->user->identity->userProfile->product_id;
        }
        if (!$product_id && !$reg) die('ERROR');

        if (!$product_id) {
            $product = Product::findOne(['is_begin' => 1, 'is_deleted' => 0]);
            if (!$product) die('ERROR');
            $product_id = $product->id;
        } else {
            $product = Product::findOne(['id' => $product_id, 'is_deleted' => 0]);
            if (!$product) die('ERROR');
        }

        $price = 0;
        $comment = '';

        if ($upgrade) {
            if (\yii::$app->user->identity->userProfile->is_complete < 2) die('ERROR');
            if (\yii::$app->user->identity->userProfile->product_id == $product_id) die('ERROR');
            if (\yii::$app->user->identity->userProfile->product->is_begin) die('ERROR');

            $price = $product->price - (\yii::$app->user->identity->userProfile->product->price);
            $comment = 'Повышение продукта до '.$product->name;
        } else {
            if ($reg && $product->is_begin) $reg = false;

            $price = ($repeat) ? $product->bonus_repeat : $product->price;
            $comment = 'Покупка '.$product->name;
            if ($reg) {
                $rp = Product::findOne(['is_begin' => 1, 'is_deleted' => 0]);
                if (!$rp) die('ERROR');
                $comment .= ' и '.$rp->name;
                $price += $rp->price;
            }
        }

        //Пересчет валюты
        $curr_price = 0;
        if ($curr == 0) {
            $xml = file_get_contents('http://www.nationalbank.kz/rss/rates_all.xml');
            if ($xml) {
                $f = simplexml_load_string($xml);
                foreach ($f->channel->item as $item) {
                    if ($item->title == 'USD') {
                        $curr_price = $price * floatval($item->description);
                        break;
                    }
                }
            } else die('ERROR');
        } else if ($curr == 1) {
            $json = file_get_contents('https://www.cbr-xml-daily.ru/daily_json.js');
            if ($json) {
                $rub = json_decode($json);
                $curr_price = $price * floatval($rub->Valute->USD->Value);
            } else die('ERROR');
        } else if ($curr == 2) {
            $curr_price = $price;
        }

        if (!$curr_price) die('ERROR');

        $this->curr_price = $curr_price;

        $type = 0;
        if ($repeat) $type = 1;
        if ($upgrade) $type = 2;

        $order = new Order();
        $order->user_id = \yii::$app->user->id;
        $order->product_id = $product_id;
        $order->amount = $price;
        $order->status = 0;
        $order->type = $type;   // 0 - первая закупка, 1 - повторная закупка, 2 - апгрейд
        $order->comment = $comment;
        $order->created_at = time();
        $order->updated_at = time();
        $order->save();

        return $order;
    }

    public function actionIndigo() {
        $order = $this->initPay();

        $body = json_encode([
            'operator_id' => \yii::$app->params['indigo']['id'],
            'order_id' => $order->id,
            'amount' => intval($this->curr_price),
            'expiration_date' => date("Y-m-d H:i:s", time() + 3600 * 24),
            'description' => $order->comment,
            'success_url' => 'https://bin.nrg-max.com/cabinet/profile?code=success',
            'fail_url' => 'https://bin.nrg-max.com/cabinet/profile?code=fail',
            'result_url' => 'https://bin.nrg-max.com/pay/indigo-result',
        ]);
        $signature = md5($body . \yii::$app->params['indigo']['key']);

        $data = [
            'body' => $body,
            'signature' => $signature
        ];

        $url = 'https://billing.indigo24.com/api/v1/payment';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded","Accept: application/json"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (!$response) return 'ERROR';

        $response = json_decode($response);
        if (isset($response->errors)) return 'ERROR';

        return $response->redirect_url;
    }

    public function actionIndigoResult() {
        $body = \yii::$app->request->post('body', null);
        $signature = \yii::$app->request->post('signature', null);

        $mysignature = md5($body . \yii::$app->params['indigo']['key']);

        if ($signature != $mysignature) return;

        $data = json_decode($body);
        if (!$data) return;

        if ($data->status != 'successful') return;

        $order = Order::findOne(['id' => $data->order_id]);
        //if ($order->amount != $data->amount) return;
        if ($order->status) return;

        $product = Product::findOne(['id' => $order->product_id]);
        $user = User::findOne(['id' => $order->user_id]);

        $indigo = new Indigo();
        $indigo->user_id = $order->user_id;
        $indigo->created_at = time();
        $indigo->status = 1;
        $indigo->amount = $data->amount;
        $indigo->save();

        $payment = new Payments();
        $payment->created_at = time();
        $payment->user_id = $order->user_id;
        $payment->type_id = 1; //Indigo
        $payment->order_id = $order->id;
        $payment->pay_id = $indigo->id;
        $payment->amount = $data->amount;
        $payment->save();

        $this->endPay(1, $order, $user, $product);
    }

    public function actionPayeer() {
        $order = $this->initPay(2);

        $id = \yii::$app->params['payeer']['id'];
        $key = \yii::$app->params['payeer']['key'];

        $comment = base64_encode($order->comment);

        $arHash = [
            $id,
            $order->id,
            number_format($order->amount, 2, '.', ''),
            'USD',
            $comment
        ];
        $arHash[] = $key;
        $signature = strtoupper(hash('sha256', implode(':', $arHash)));

        $r = '';
        $r .= '<input type="hidden" name="m_shop" value="'.$id.'">';
        $r .= '<input type="hidden" name="m_orderid" value="'.$order->id.'">';
        $r .= '<input type="hidden" name="m_amount" value="'.number_format($order->amount, 2, '.', '').'">';
        $r .= '<input type="hidden" name="m_curr" value="USD">';
        $r .= '<input type="hidden" name="m_desc" value="'.$comment.'">';
        $r .= '<input type="hidden" name="m_sign"value="'.$signature.'">';
        //$r .= '<input type="hidden" name="m_process" value="send" />';

        return $r;
    }

    public function actionPayeerResult() {
        if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return;

        if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
        {
            $m_key = \yii::$app->params['payeer']['key'];

            $arHash = array(
                $_POST['m_operation_id'],
                $_POST['m_operation_ps'],
                $_POST['m_operation_date'],
                $_POST['m_operation_pay_date'],
                $_POST['m_shop'],
                $_POST['m_orderid'],
                $_POST['m_amount'],
                $_POST['m_curr'],
                $_POST['m_desc'],
                $_POST['m_status']
            );

            if (isset($_POST['m_params']))
            {
                $arHash[] = $_POST['m_params'];
            }

            $arHash[] = $m_key;

            $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

            if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success' && $_POST['m_curr'] == 'USD' && $_POST['m_amount'] > 0)
            {
                $pay = Payeer::find()->where(['operation_id' => $_POST['m_operation_id']])->one();
                if ($pay) {
                    echo $_POST['m_orderid'].'|success';
                    return;
                }

                $order = Order::findOne(['id' => $_POST['m_orderid']]);
                if ($order->status) {
                    echo $_POST['m_orderid'].'|success';
                    return;
                }

                $product = Product::findOne(['id' => $order->product_id]);
                $user = User::findOne(['id' => $order->user_id]);

                $pay = new Payeer();
                $pay->user_id = $order->user_id;
                $pay->operation_date = $_POST['m_operation_date'];
                $pay->operation_id = $_POST['m_operation_id'];
                $pay->operation_pay_date = $_POST['m_operation_pay_date'];
                $pay->amount = $_POST['m_amount'];
                if (isset($_POST['summa_out']) && $_POST['summa_out']) $pay->summa_out = $_POST['summa_out'];
                $pay->curr = $_POST['m_curr'];
                if (isset($_POST['transfer_id']) && $_POST['transfer_id']) $pay->transfer_id = $_POST['transfer_id'];
                if (isset($_POST['client_email']) && $_POST['client_email']) $pay->client_email = $_POST['client_email'];
                if (isset($_POST['client_account']) && $_POST['client_account']) $pay->client_account = $_POST['client_account'];
                $pay->save(false);

                $payment = new Payments();
                $payment->created_at = time();
                $payment->order_id = $order->id;
                $payment->user_id = $order->user_id;
                $payment->type_id = 2; //PAYEER
                $payment->pay_id = $pay->id;
                $payment->amount = $_POST['summa_out'];
                $payment->save();

                $this->endPay(2, $order, $user, $product);

                echo $_POST['m_orderid'].'|success';
                return;
            }

            echo $_POST['m_orderid'].'|error';
        }
    }

    public function actionBox()
    {
        $order = $this->initPay();

        $paybox = new Paybox();
        $paybox->merchant->id = \Yii::$app->params['paybox']['id'];
        $paybox->merchant->secretKey = \Yii::$app->params['paybox']['key'];
        $paybox->merchant->user_id = \Yii::$app->user->id;
        $paybox->merchant->curr_price = $this->curr_price;
        $paybox->merchant->pg_success_url = 'https://bin.nrg-max.com/cabinet/profile?code=success';
        $paybox->merchant->pg_success_url_method = 'AUTOPOST';
        $paybox->merchant->pg_failure_url = 'https://bin.nrg-max.com/cabinet/profile?code=fail';
        $paybox->merchant->pg_failure_url_method = 'AUTOPOST';
        $paybox->merchant->pg_site_url = 'https://bin.nrg-max.com/cabinet/index';
        $paybox->order->description = $order->comment;
        $paybox->order->amount = intval($this->curr_price);
        $paybox->order->id = $order->id;

        if ($paybox->init()) {
            //header('Location:' . $paybox->redirectUrl);
            return $this->redirect($paybox->redirectUrl);
        }

        return 'ERROR';
    }

    public function actionBoxCheck()
    {
        $paybox = new Paybox();

        $paybox->merchant->id = \Yii::$app->params['paybox']['id'];
        $paybox->merchant->secretKey = \Yii::$app->params['paybox']['key'];

        $request = (array_key_exists('pg_xml', $_REQUEST))
            ? $paybox->parseXML($_REQUEST)
            : $_REQUEST;

        if(!$paybox->checkSig($request)) {
            throw new ForbiddenHttpException();
        }

        $user_id = $request['user_id'];

        if (!$user_id) {
            echo $paybox->error('User not found'); die();
        }

        $user = User::findOne(['id' => $user_id, 'status' => 1]);
        if (!$user) {
            echo $paybox->error('User not found'); die();
        }

        $order_id = $request['pg_order_id'];
        $order = Order::findOne(['id' => $order_id, 'user_id' => $user_id, 'status' => 0]);
        if (!$order) {
            echo $paybox->error('Order not found'); die();
        }

        $curr = $request['pg_currency'];
        if ($curr != 'KZT') {
            echo $paybox->error('Currency not supported'); die();
        }

        $price = $request['pg_amount'];
        if (!$price) {
            echo $paybox->error('Bad amount'); die();
        }

        $curr_price = $request['pg_amount'];
        $curr_price_need = $request['curr_price'];
        if ($curr_price != $curr_price_need) {
            echo $paybox->error('Bad amount'); die();
        }

        echo $paybox->accept('Ok');die();
    }

    public function actionBoxResult()
    {
        $paybox = new Paybox();

        $paybox->merchant->id = \Yii::$app->params['paybox']['id'];
        $paybox->merchant->secretKey = \Yii::$app->params['paybox']['key'];

        $request = (array_key_exists('pg_xml', $_REQUEST))
            ? $paybox->parseXML($_REQUEST)
            : $_REQUEST;

        if(!$paybox->checkSig($request)) {
            throw new ForbiddenHttpException();
        }

        $messageLog = [
            'status' => 'Получены данные платежа.',
            'post' => $request
        ];
        //\Yii::info($messageLog, 'payment_fail');

        $user_id = $request['user_id'];

        if (!$user_id) {
            echo $paybox->error('User not found'); die();
        }

        $user = User::findOne(['id' => $user_id, 'status' => 1]);
        if (!$user) {
            echo $paybox->error('User not found'); die();
        }

        $order_id = $request['pg_order_id'];
        $order = Order::findOne(['id' => $order_id, 'user_id' => $user_id]);
        if (!$order) {
            echo $paybox->error('Order not found'); die();
        }
        if ($order->status) {
            echo $paybox->accept('Ok'); die();
        }

        $curr = $request['pg_currency'];
        if ($curr != 'KZT') {
            echo $paybox->cancel('Currency not supported'); die();
        }

        $price = $request['pg_amount'];
        if (!$price) {
            echo $paybox->cancel('Bad amount'); die();
        }

        $curr_price = $request['pg_amount'];
        $curr_price_need = $request['curr_price'];
        if ($curr_price != $curr_price_need) {
            echo $paybox->error('Bad amount'); die();
        }

        if ($request['pg_testing_mode']) {
            echo $paybox->accept('Ok'); die();
        }

        $product = Product::findOne(['id' => $order->product_id]);
        $user = User::findOne(['id' => $order->user_id]);

        $payboxs = new PayBoxs();
        $payboxs->user_id = $order->user_id;
        $payboxs->created_at = time();
        $payboxs->status = 1;
        $payboxs->amount = $curr_price;
        $payboxs->pg_payment_id = $request['pg_payment_id'];
        $payboxs->json_request = json_encode($request);
        $payboxs->save();

        $payment = new Payments();
        $payment->created_at = time();
        $payment->order_id = $order->id;
        $payment->user_id = $order->user_id;
        $payment->type_id = 3; //PayBox
        $payment->pay_id = $payboxs->id;
        $payment->amount = $price;
        $payment->save();

        $this->endPay(3, $order, $user, $product);

        echo $paybox->accept('Ok. Order will be prepared'); die();
    }

    public function actionBoxRefund()
    {
        $paybox = new Paybox();

        $paybox->merchant->id = \Yii::$app->params['paybox']['id'];
        $paybox->merchant->secretKey = \Yii::$app->params['paybox']['key'];

        $request = (array_key_exists('pg_xml', $_REQUEST))
            ? $paybox->parseXML($_REQUEST)
            : $_REQUEST;

        if($paybox->checkSig($request)) {
            $user_id = $request['user_id'];
            $user = User::findOne(['id' => $user_id, 'status' => 1]);

            //Тут будет отмена платежа если что
            $task_id = \Yii::$app->queue->push(new NotificationJob([
                'user_id' => 2,
                'is_email' => 1,
                'is_sms' => 1,
                'object' => 'Admin',
                'message' => 'Произошла отмена платежа: ' . $user->userProfile->fio,
            ]));

            return $paybox->refunded();
        }

        throw new ForbiddenHttpException();
    }

    public function actionBoxCapture()
    {
        $paybox = new Paybox();

        $paybox->merchant->id = \Yii::$app->params['paybox']['id'];
        $paybox->merchant->secretKey = \Yii::$app->params['paybox']['key'];

        $request = (array_key_exists('pg_xml', $_REQUEST))
            ? $paybox->parseXML($_REQUEST)
            : $_REQUEST;

        if($paybox->checkSig($request)) {
            return $paybox->captured();
        }

        throw new ForbiddenHttpException();
    }

    public function actionRobo()
    {
        $order = $this->initPay(0);

        // your registration data
        $mrh_login = \Yii::$app->params['robokassa']['id'];             // your login here
        $mrh_pass1 = \Yii::$app->params['robokassa']['password_1'];     // merchant pass1 here

        $inv_id    = $order->id;                                        // shop's invoice number
        $inv_desc  = $order->comment;                                   // invoice desc
        $out_summ  = number_format($this->curr_price, 0, '.', '');      // invoice summ

        $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

        //Тестовый
        //$url = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$mrh_login&".
        //    "OutSum=$out_summ&InvId=$inv_id&Description=$inv_desc&SignatureValue=$crc&IsTest=1";

        $url = "https://auth.robokassa.ru/Merchant/Index.aspx?MerchantLogin=$mrh_login&".
            "OutSum=$out_summ&InvId=$inv_id&Description=$inv_desc&SignatureValue=$crc";

        return $url;
    }

    public function actionRoboResult()
    {
        $out_summ = $price = \yii::$app->request->post("OutSum");
        $inv_id = $order_id = \yii::$app->request->post("InvId");
        //$shp_count = \yii::$app->request->post("shp_count");
        //$shp_item = \yii::$app->request->post("shp_item");
        //$shp_user = \yii::$app->request->post("shp_user");
        $crc = \yii::$app->request->post("SignatureValue");

        $IncCurrLabel = \yii::$app->request->post("IncCurrLabel", null);
        $EMail = \yii::$app->request->post("EMail", null);
        $Fee = \yii::$app->request->post("Fee", null);
        $IncSum = \yii::$app->request->post("IncSum", null);
        $PaymentMethod = \yii::$app->request->post("PaymentMethod", null);

        $crc = strtoupper($crc);
        //$out = print_r(\yii::$app->request->post(), true);

        //$my_crc = strtoupper(md5("$out_summ:$inv_id:".\yii::$app->params['robokassa']['password_2'].":shp_count=$shp_count:shp_item=$shp_item:shp_user=$shp_user"));
        $my_crc = strtoupper(md5("$out_summ:$inv_id:".\yii::$app->params['robokassa']['password_2']));

        // проверка корректности подписи
        // check signature
        if ($my_crc != $crc)
        {
            echo "bad sign\n";
            exit();
        }

        $order = Order::findOne(['id' => $order_id]);
        if (!$order) {
            echo 'Order not found'; die();
        }
        if ($order->status) {
            echo "OK$inv_id\n"; die();
        }

        $user_id = $order->user_id;

        if (!$user_id) {
            echo 'User not found'; die();
        }

        $user = User::findOne(['id' => $user_id, 'status' => 1]);
        if (!$user) {
            echo 'User not found'; die();
        }

        $product = Product::findOne(['id' => $order->product_id]);

        $request_json = [
            'OutSum' => $out_summ,
            'InvId' => $inv_id,
            //'shp_item' => $shp_item,
            //'shp_user' => $shp_user,
            'IncCurrLabel' => $IncCurrLabel,
            'EMail' => $EMail,
            'Fee' => $Fee,
            'IncSum' => $IncSum,
            'PaymentMethod' => $PaymentMethod,
        ];
        $request = json_encode($request_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $robo = new Robo();
        $robo->user_id = $order->user_id;
        $robo->created_at = time();
        $robo->status = 1;
        $robo->amount = $out_summ;
        $robo->json_request = $request;
        $robo->save();

        $payment = new Payments();
        $payment->created_at = time();
        $payment->order_id = $order->id;
        $payment->user_id = $order->user_id;
        $payment->type_id = 4; //Robokassa
        $payment->pay_id = $robo->id;
        $payment->amount = $out_summ;
        $payment->save();

        $this->endPay(4, $order, $user, $product);

        // признак успешно проведенной операции
        // success
        echo "OK$inv_id\n";
    }

    public function actionBalance()
    {
        $order = $this->initPay(2);
        if ($order->type == 0) {
            echo 'Регистрация с баланса не поддерживается'; die();
        }

        $user_id = \yii::$app->user->id;
        $user = User::findOne(['id' => $user_id, 'status' => 1]);
        if (!$user) {
            echo 'Пользователь не найден'; die();
        }

        if ($user->userProfile->balance < $order->amount) {
            echo 'Не хватает средст на балансе'; die();
        }

        $product = Product::findOne(['id' => $order->product_id]);
        if (!$product) {
            echo 'Продукт не найден'; die();
        }

        $payment = new Payments();
        $payment->created_at = time();
        $payment->order_id = $order->id;
        $payment->user_id = $order->user_id;
        $payment->type_id = 0; //Баланс
        $payment->pay_id = 0;
        $payment->amount = $order->amount;
        $payment->save();

        \Yii::$app->db->createCommand('UPDATE '.\app\models\UserProfile::tableName().' SET balance = balance - '.number_format($order->amount, 2, '.', '').' WHERE user_id = ' . $order->user_id)->execute();

        $this->endPay(100, $order, $user, $product);

        return "OK";
    }

    public function endPay($type_id, $order, $user, $product) {
        $order->status = 1;
        $order->updated_at = time();
        $order->save();

        if ($order->type == 0) {
            //Регистрация
            \Yii::$app->db->createCommand('UPDATE '.\app\models\UserProfile::tableName().' SET product_id = '.$order->product_id.' WHERE user_id = ' . $order->user_id)->execute();
            \Yii::$app->db->createCommand('UPDATE ' . \app\models\UserProfile::tableName() . ' SET is_complete = 2 WHERE user_id = ' . $order->user_id)->execute();

            $user->Approve();
            $user->save(false);

            \Yii::$app->queue->push(new RecalcJob([
                'user_id' => $user->id,
            ]));

            $task_id = \Yii::$app->queue->push(new NotificationJob([
                'user_id' => 2,
                'is_email' => 1,
                'is_sms' => 1,
                'object' => 'Admin',
                'message' => 'Оплата регистрации: ' . $user->userProfile->fio,
            ]));
        } else if ($order->type == 1) {
            //Повторная закупка
            \Yii::$app->db->createCommand('UPDATE ' . \app\models\UserProfile::tableName() . ' SET is_zakup = 0 WHERE user_id = ' . $order->user_id)->execute();
            \Yii::$app->db->createCommand('UPDATE ' . \app\models\UserProfile::tableName() . ' SET is_zakup_complete = 1 WHERE user_id = ' . $order->user_id)->execute();

            \Yii::$app->queue->push(new RecalcJob([
                'user_id' => $order->user_id,
                'zakupka' => $product->balls,
            ]));

            $task_id = \Yii::$app->queue->push(new NotificationJob([
                'user_id' => 2,
                'is_email' => 1,
                'is_sms' => 1,
                'object' => 'Admin',
                'message' => 'Повторная покупка: ' . $user->userProfile->fio,
            ]));
        } else {
            $old_product = Product::findOne(['id' => $user->userProfile->product_id]);
            \Yii::$app->db->createCommand('UPDATE '.\app\models\UserProfile::tableName().' SET product_id = '.$product->id.' WHERE user_id = ' . $order->user_id)->execute();

            \Yii::$app->queue->push(new RecalcJob([
                'user_id' => $order->user_id,
                'balls' => $product->balls - $old_product->balls,
                'old_product_id' => $old_product->id,
                'amount' => $product->price - $old_product->price,
            ]));

            $task_id = \Yii::$app->queue->push(new NotificationJob([
                'user_id' => 2,
                'is_email' => 1,
                'is_sms' => 1,
                'object' => 'Admin',
                'message' => 'Повышение продукта: ' . $user->userProfile->fio . ' с '. $old_product->name . ' на ' . $product->name,
            ]));

            //Флаг что пользователь требует закупку
            \Yii::$app->db->createCommand('UPDATE '.\app\models\UserProfile::tableName().' SET is_upgrade = '.$old_product->id.' WHERE user_id = ' . $order->user_id)->execute();
        }
    }
}
