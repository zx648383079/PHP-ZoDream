<?php
namespace Module\Auth\Service\Api\Admin;


use Module\Auth\Domain\Concerns\AdminRole;
use Module\OpenPlatform\Domain\Concerns\ApiPlatformOption;
use Zodream\Route\Controller\RestController;

class OauthController extends RestController {

    use AdminRole, ApiPlatformOption;

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