<?php
namespace Module\Shop\Service\Api;


use Infrastructure\Uploader;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Repositories\AccountRepository;
use Module\Shop\Domain\Models\BankCardModel;
use Module\Shop\Domain\Models\CertificationModel;

class AccountController extends Controller {

    public function rules() {
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
        $card_list = BankCardModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        foreach ($card_list as $item) {
            $item['icon'] = url()->asset('assets/images/wap_logo.png');
        }
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
        $cert = CertificationModel::where('user_id', auth()->id())->first();
        return $this->render([
            'data' => $cert ? $cert : false
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
        $cert = CertificationModel::where('user_id', auth()->id())->first();
        if (empty($cert)) {
            $cert = new CertificationModel();
        }
        $cert->load();
        $cert->user_id = auth()->id();
        if (!$cert->save()) {
            return $this->renderFailure($cert->getFirstError());
        }
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