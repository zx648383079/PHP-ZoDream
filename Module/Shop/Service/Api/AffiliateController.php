<?php
namespace Module\Shop\Service\Api;

use Module\Auth\Domain\Model\AccountLogModel;

class AffiliateController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
    }

    public function userAction() {
        $card_list = AccountLogModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        $card_list->clearAttribute()->setPage([]);
        return $this->renderPage($card_list);
    }

    public function orderAction() {
        $card_list = AccountLogModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        $card_list->clearAttribute()->setPage([]);
        return $this->renderPage($card_list);
    }

    public function subtotalAction() {
        return $this->render([
           'money' => 0,
        ]);
    }
}