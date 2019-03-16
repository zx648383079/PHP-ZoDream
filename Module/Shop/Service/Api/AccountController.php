<?php
namespace Module\Shop\Service\Api;


use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\OAuthModel;

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

    public function connectAction() {
        $model_list = OAuthModel::where('user_id', auth()->id())
            ->get('id', 'vendor', 'nickname', 'created_at');
        return $this->render($model_list);
    }
}