<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin\Payment;

use Module\Shop\Domain\Plugin\BasePayment;
use Module\Shop\Domain\Plugin\IPaymentPlugin;
use Zodream\Disk\File;
use Zodream\Http\Http;
use Zodream\Infrastructure\Support\Process;

class Boc extends BasePayment implements IPaymentPlugin {

    public function getName(): string {
        return '中行支付';
    }

    public function preview(): string {
        return '';
    }

    public function pay(array $log): array {
        $data = [
            'merchantNo'        => $this->getConf('mer_id'),                 // 商户号
            'payType'           => 1,                    // 支付类型
            'orderNo'           => $log['payment_id'],                    // 订单号
            'curCode'           => '001',                    // 订单币种
            'orderAmount'       => sprintf('%.2f', $log['currency_money']),                // 订单金额
            'orderTime'         => date('YmdHis'),                  // 订单时间
            'orderNote'         => ' '.$log['order_id'],                  // 订单说明
            'orderUrl'          => $log['return_url'],                   // 商户接收通知URL
            'orderTimeoutDate'  => date('YmdHis', time() + 300),           // 超时时间
            'terminalChnl' => '08',
            'tradeType' => 'WXAPP',
            'deviceInfo' => 'WEB',
            'body'       => $log['body'],
            'spbillCreateIp' => $log['ip'],
            'signData'          => ''                    // 商户签名数据
        ];

        $data['signData'] = $this->sign($data);
        $res = $this->request('B2CRecvOrder.do', $data);
        if ($res['header']['hdlSts'] != 'A') {
            throw new \Exception($res['header']['rtnMsg']. $res['body']['tranMsg']);
        }
        return $this->toUrl($res['body']['qrCode']);
    }

    public function callback(array $input): array {
        if (!$this->verify($input)) {
            return [
                'status' => self::STATUS_FAILURE,
                'output' => 'Invalid Sign'
            ];
        }
        if ($input['returnActFlag'] != 1 || $input['orderStatus'] != 1) {
            return [
                'status' => self::STATUS_FAILURE,
                'output' => 'Invalid Status'
            ];
        }
        return [
            'status' => self::STATUS_SUCCESS,
            'payment_id' => $input['orderNo'],
            'money' => $input['payAmount'],
            'payed_at' => strtotime($input['payTime']),
            'trade_no' => $input['orderSeq']
        ];
    }

    public function refund(array $log): array {
        $data = [
            'merchantNo'      => $this->getConf('mer_id'),                 // 商户号
            'mRefundSeq'      => $log['refund_id'],
            'curCode'         => '001',
            'refundAmount'    => sprintf('%.2f', $log['currency_money']),
            'orderNo'         => $log['payment_id'],
            'signData'        => ''                    // 商户签名数据
        ];

        $data['signData'] = $this->sign($data, [
            'merchantNo', 'mRefundSeq',
            'curCode', 'refundAmount', 'orderNo'
        ]);
        $res = $this->request('RefundOrder.do', $data);
        if ($res && $res['header']['dealStatus'] === '0') {
            $log['tran_no'] = $res['body']['orderSeq'];
            $log['status'] = self::STATUS_SUCCESS;
            return $log;
        }
        if ($res && $res['header']['dealStatus'] === '2') {
            $log['tran_no'] = $res['body']['orderSeq'];
            $log['status'] = 'WAIT';
            return $log;
        }
        $log['status'] = self::STATUS_FAILURE;
        return $log;
    }

    public function query($order_no) {
        $data = [
            'merchantNo' => $this->getConf('mer_id'),
            'orderNo' => $order_no,
        ];
        $data['signData'] = $this->sign($data, ['merchantNo', 'orderNo']);
        $res = $this->request('QueryOrderTrans.do', $data);
        if ($res && isset($res['body']['orderTrans']['orderNo'])) {
            $res['body']['orderTrans'] = [$res['body']['orderTrans']];
        }
        return $res;
    }

    /**
     * 验证交易是否成功
     * @param $order_no
     * @param string $tranCode
     * @return bool
     */
    public function checkTranSuccess($order_no, $tranCode = '01') {
        if (empty($order_no)) {
            return false;
        }
        $res = $this->query($order_no);
        if (!$res || $res['header']['hdlSts'] !== 'A') {
            return false;
        }
        foreach($res['body']['orderTrans'] as $item) {
            if ($item['tranCode'] === $tranCode && $item['tranStatus'] === '1') {
                return $item;
            }
        }
        return false;
    }

    public function settings(): array {
        return [
            'mer_id' => [
                'label' => '商户代码',
            ],
            'sign_cert_path' => [
                'label' => '私钥证书',
                'type' => 'file',
                'tip' => '文件后缀名为.pfx'
            ],
            'sign_cert_pwd' => [
                'label' => '私钥证书密码',
            ],
            'verify_cert_path' => [
                'label' => '公钥证书',
                'type' => 'file',
                'tip' => '文件后缀名为.cer'
            ],
            'pkcs7_path' => [
                'label' => 'pkcs7文件',
                'type' => 'file',
                'tip' => '文件后缀名为.jar'
            ],
            'java_path' => [
                'label' => 'java执行路径',
                'tip' => '安装目录下bin/java'
            ]
        ];
    }

    protected function getUrl($path) {
        return sprintf('https://%s/PGWPortal/%s', $this->getConf('debug') ? '101.231.206.170' : 'ebspay.boc.cn', trim($path, '/'));
    }

    public function sign(array $data, $sign_word = null){
        $sign_word = $this->getSignWord($data, empty($sign_word) ? [
            'orderNo', 'orderTime', 'curCode', 'orderAmount', 'merchantNo'
        ]: $sign_word);
        $sourceFile = $this->getTempFile('source'.time());
        $targetFile = $this->getTempFile('target'.time());
        $sourceFile->write($sign_word);
        $this->createProcess('com.bocnet.common.security.P7Sign',
            $this->getConf('sign_cert_path'),
            $this->getConf('sign_cert_pwd'),
            $sourceFile,
            $targetFile)->start()->join()->stop();
        $sign = $targetFile->read();
        $sourceFile->delete();
        $targetFile->delete();
        return $sign;
    }

    public function verify(array $data, $sign_word = null) {
        $sign_word = $this->getSignWord($data, empty($sign_word) ? [
            'merchantNo', 'orderNo', 'orderSeq', 'cardTyp', 'payTime',
            'orderStatus', 'payAmount'
        ]: $sign_word);
        $sign = $data['signData'];
        $sourceFile = $this->getTempFile('source'.time());
        $targetFile = $this->getTempFile('target'.time());
        $sourceFile->write($sign);
        $targetFile->write($sign_word);
        $process = $this->createProcess('com.bocnet.common.security.P7Verify',
            $this->getConf('verify_cert_path'),
            $sourceFile,
            $targetFile);
        $process->start()->join()->stop();
        $res = strpos($process->getStdout(), 'VERIFY OK') !== false;
        $sourceFile->delete();
        $targetFile->delete();
        return $res;
    }

    private function getSignWord(array $data, $sign_word) {
        if (!is_array($sign_word)) {
            return $sign_word;
        }
        $items = [];
        foreach($sign_word as $k) {
            $items[] = $data[$k];
        }
        return implode('|', $items);
    }

    /**
     * @param $name
     * @return File
     */
    private function getTempFile($name) {
        return app_path()->directory('data/cache')->file($name);
    }

    /**
     * @param $package
     * @param $args
     * @return Process
     */
    private function createProcess($package, $args) {
        $java = $this->getConf('java_path');
        $args = [
            $java ? $java : 'java',
            '-classpath',
            $this->getConf('pkcs7_path'),
            implode(' ', func_get_args())
        ];
        return Process::factory(implode(' ', $args));
    }

    private function request($path, array $data) {
        $res = (new Http($this->getUrl($path)))
            ->maps($data)->post();
        if (empty($res)) {
            return $res;
        }
        return $this->decodeXml($res);
    }

    private function decodeXml($xml) {
        $disableLibxmlEntityLoader = libxml_disable_entity_loader(true);
        $hasR = strpos($xml, '&') !== false;
        $PATTERN = 'br_boc_br';
        if ($hasR) {
            $xml = str_replace('&', $PATTERN, $xml);
        }
        $args = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)),true);
        libxml_disable_entity_loader($disableLibxmlEntityLoader);
        if (isset($args['body']['qrCode']) && $hasR) {
            $args['body']['qrCode'] = str_replace($PATTERN, '&', $args['body']['qrCode']);
            if ($this->getConf('debug')) {
                $args['body']['qrCode'] = 'http://wcbt4.bjyada.com/merserwcbweb_t4/pay/index.do?qrCode='. urlencode($args['body']['qrCode']);
            }
        }
        return $args;
    }
}
