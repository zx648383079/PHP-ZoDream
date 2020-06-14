<?php
namespace Module\Shop\Service\Api;


use Infrastructure\Uploader;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Repositories\AccountRepository;

class AccountController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
    }

    public function logAction() {
        $log_list = AccountLogModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        return $this->renderPage($log_list);
    }

    public function cardAction() {
        $card_list = AccountLogModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        $card_list->clearAttribute()->setPage([
           [
                'id' => 1,
                'type' => 1,
                'icon' => url()->asset('assets/images/wap_logo.png'),
                'bank' => '工商银行',
                'card_number' => '*** **** **** 5555',
                'status' => 1,
                'created_at' => ''
           ],
            [
                'id' => 2,
                'type' => 0,
                'icon' => url()->asset('assets/images/wap_logo.png'),
                'bank' => '邮政储蓄银行',
                'card_number' => '*** **** **** 6666',
                'status' => 0,
                'created_at' => ''
            ]
        ]);
        return $this->renderPage($card_list);
    }

    public function addCardAction() {
        return $this->render([
            'data' => false
        ]);
    }

    public function connectAction() {
        $model_list = AccountRepository::getConnect();
        return $this->render($model_list);
    }

    public function certificationAction() {
        return $this->render([
            'data' => false
        ]);
    }

    public function uploadCertificationAction() {
        $upload = new Uploader('file', [
            'pathFormat' => '/assets/upload/cert/{yyyy}{mm}{dd}/{time}{rand:6}',
            'maxSize' => 2048000,
            'allowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp']
        ]);
        $data = $upload->getFileInfo();
        if ($data['state'] !== 'SUCCESS') {
            return $this->renderFailure($data['state']);
        }
        return $this->render([
            'data' => $data['url']
        ]);
    }

    public function saveCertificationAction() {
        return $this->render([
            'data' => true
        ]);
    }

    public function subtotalAction() {
        return $this->render([
           'money' => 0,
           'integral' => 0,
           'bonus' => 0,
           'coupon' => 0
        ]);
    }
}