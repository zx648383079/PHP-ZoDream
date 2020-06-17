<?php
namespace Module\Shop\Domain\Plugin\Payment;

use Module\Auth\Domain\Model\OAuthModel;
use Module\OpenPlatform\Domain\Platform;
use Module\Shop\Domain\Plugin\BasePayment;
use Zodream\Helpers\Str;
use Zodream\ThirdParty\Pay\WeChat as Payer;

class Wechat extends BasePayment {

    public function getName(): string {
        return '微信支付';
    }

    public function preview(): string {
        // TODO: Implement preview() method.
    }

    public function pay(array $log): array {
        $openid = app('request')->get('openid');
        if (empty($openid)) {
            $openid = OAuthModel::findOpenid(auth()->id(), OAuthModel::TYPE_WX_MINI, Platform::platformId());
        }
        $api = $this->getApi();
        $data = [
            'nonce_str' => Str::random(32),
            'body' => sprintf('%s-%s', $api->get('mch_name'), $log['body']),
            'out_trade_no' => $log['payment_id'],
            'total_fee' => $log['currency_money'] * 100,
            'spbill_create_ip' => $log['ip'],
            'time_start' => date('YmdHis'),
            'trade_type' => 'JSAPI',
            'notify_url' => $log['notify_url'],
            'openid' => $openid,
        ];
        $order = $api->order($data);
        $args = $api->jsPay($order);
        return [
            'params' => $args
        ];
    }

    public function callback(array $input): array {
        $api = $this->getApi();
        $input = $api->callback();
        if (!isset($input['result_code']) || $input['result_code'] !== 'SUCCESS') {
            return [
                'status' => self::STATUS_FAILURE,
                'output' => isset($input['err_code_des']) ? $input['err_code_des'] : '支付失败',
            ];
        }
        return [
            'status' => self::STATUS_SUCCESS,
            'payment_id' => $input['out_trade_no'],
            'money' => $input['total_fee'] / 100,
            'payed_at' => strtotime($input['time_end']),
            'trade_no' => $input['transaction_id'],
            'output' => $api->notifySuccess()
        ];
    }

    public function refund(array $log): array {
        // TODO: Implement refund() method.
    }

    /**
     * @return Payer
     * @throws \Exception
     */
    protected function getApi() {
        if (!app()->has(Platform::PLATFORM_KEY)) {
            return new Payer();
        }
        /** @var Platform $platform */
        $platform = app(Platform::PLATFORM_KEY);
        return new Payer($platform->option('wechat_pay'));
    }
}