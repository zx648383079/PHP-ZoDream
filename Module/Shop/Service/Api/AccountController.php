<?php
namespace Module\Shop\Service\Api;


use Domain\Repositories\FileRepository;
use Infrastructure\Uploader;
use Module\Auth\Domain\Repositories\AccountRepository;
use Module\Shop\Domain\Repositories\AccountRepository as ShopAccount;
use Module\Shop\Domain\Models\BankCardModel;
use Module\Shop\Domain\Models\CertificationModel;
use Zodream\Infrastructure\Contracts\Http\Input;

class AccountController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
    }

    public function logAction() {
        return $this->renderPage(
            ShopAccount::logList()
        );
    }

    public function cardAction() {
        return $this->renderPage(ShopAccount::bankCardList());
    }

    public function addCardAction() {
        return $this->renderData(false);
    }

    public function connectAction() {
        $model_list = AccountRepository::getConnect();
        return $this->render($model_list);
    }

    public function certificationAction() {
        return $this->renderData(ShopAccount::certification());
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
        return $this->renderData($data['url']);
    }

    public function saveCertificationAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,20',
                'sex' => 'string',
                'country' => 'string:0,20',
                'type' => 'int:0,127',
                'card_no' => 'required|string:0,30',
                'expiry_date' => 'string:0,30',
                'profession' => 'string:0,30',
                'address' => 'string:0,200',
                'front_side' => 'string:0,200',
                'back_side' => 'string:0,200',
            ]);
            ShopAccount::saveCertification($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
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