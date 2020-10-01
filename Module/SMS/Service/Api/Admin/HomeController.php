<?php
namespace Module\SMS\Service\Api\Admin;

use Module\OpenPlatform\Domain\Concerns\ApiPlatformOption;

class HomeController extends Controller {
    use ApiPlatformOption;

    public function indexAction() {
        return $this->renderData([]);
    }

    protected function platformOption() {
        return [
            'ihuyi_sms' => [
                '_label' => '互亿无线',
                'account' => '',
                'password' => '',
            ],
            'ali_sms' => [
                '_label' => '阿里短信',
                'AccessKeyId' => '',
                'secret' => '',
            ],
        ];
    }
}