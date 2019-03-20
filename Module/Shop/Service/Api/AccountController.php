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

    public function connectAction() {
        $model_list = OAuthModel::where('user_id', auth()->id())
            ->get('id', 'vendor', 'nickname', 'created_at');
        return $this->render($model_list);
    }
}