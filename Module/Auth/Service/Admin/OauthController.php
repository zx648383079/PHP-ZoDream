<?php
declare(strict_types=1);
namespace Module\Auth\Service\Admin;

use Module\OpenPlatform\Domain\Concerns\EditPlatformOption;

class OauthController extends Controller {

    use EditPlatformOption;

    public function indexAction() {
        return $this->show();
    }


    protected function platformOption() {
        return [
            'qq_auth' => [
                '_label' => 'QQ登录',
                'client_id' => '',
                'client_secret' => '',
            ],
            'wechat_auth' => [
                '_label' => '微信登录',
                'appid' => '',
                'secret' => ''
            ],
            'mini_auth' => [
                '_label' => '微信小程序登录',
                'appid' => '',
                'secret' => ''
            ],
            'github_auth' => [
                '_label' => 'GITHUB登录',
                'client_id' => '',
                'client_secret' => '',
            ]
        ];
    }
}