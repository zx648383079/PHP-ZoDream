<?php
namespace Module\Shop\Service\Api;

use Module\Auth\Domain\Model\UserSimpleModel;
use Module\Shop\Domain\Plugin\Affiliate\AffiliateLogModel;

class AffiliateController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
    }

    public function userAction() {
        $user_list = UserSimpleModel::where('parent_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        return $this->renderPage($user_list);
    }

    public function orderAction() {
        $log_list = AffiliateLogModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        return $this->renderPage($log_list);
    }

    public function subtotalAction() {
        return $this->render([
           'money' => 0,
        ]);
    }
}