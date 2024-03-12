<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Auth\Domain\Repositories\AccountRepository;
use Module\Shop\Domain\Repositories\AccountRepository as ShopAccount;
use Zodream\Infrastructure\Contracts\Http\Input;

class AccountController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
    }

    public function logAction(string $keywords = '', int $type = 0) {
        return $this->renderPage(
            AccountRepository::logList($keywords, $type, auth()->id())
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

    public function uploadCertificationAction(Input $input) {
        try {
            $data = ShopAccount::storage()->addFile($input->file('file'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
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
        return $this->render(ShopAccount::subtotal());
    }
}