<?php
namespace Module\Auth\Service\Admin;


use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformOptionModel;

class OauthController extends Controller {

    public function indexAction() {
        return $this->show();
    }


    public function optionAction() {
        $items = PlatformModel::findWidthAuth();
        return $this->show(compact('items'));
    }

    public function editOptionAction($platform_id) {
        $this->layout = false;
        $platform = PlatformModel::findWithAuth($platform_id);
        if (empty($platform)) {
            return '';
        }
        $data = PlatformOptionModel::getStores($platform_id, [
            'qq_auth' => [
                'label' => 'QQ登录',
                'client_id' => '',
                'client_secret' => '',
            ],
            'wechat_auth' => [
                'label' => '微信登录',
                'appid' => '',
                'secret' => ''
            ],
            'mini_auth' => [
                'label' => '微信小程序登录',
                'appid' => '',
                'secret' => ''
            ],
            'github_auth' => [
                'label' => 'GITHUB登录',
                'client_id' => '',
                'client_secret' => '',
            ]
        ]);
        return $this->show(compact('data'));
    }

    public function saveOptionAction($platform_id, $option) {
        PlatformOptionModel::saveOption($platform_id, $option);
        return $this->jsonSuccess(null, '保存成功');
    }
}