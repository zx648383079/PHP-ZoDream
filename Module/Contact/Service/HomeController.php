<?php
declare(strict_types=1);
namespace Module\Contact\Service;

use Module\Contact\Domain\Repositories\ContactRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class HomeController extends ModuleController {

    public function rules() {
        return [
            'unsubscribe' => '*',
            '*' => 'p'
        ];
    }

    public function indexAction() {

    }

    public function feedbackAction(Request $request) {
        try {
            ContactRepository::saveFeedback($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(null, '提交留言成功');
    }

    public function subscribeAction(Request $request) {
        try {
            ContactRepository::saveSubscribe($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(null, '订阅成功');
    }

    public function unsubscribeAction(string $email) {
        try {
            ContactRepository::unsubscribe($email);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(null, '已取消订阅');
    }

    public function friendLinkAction(Request $request) {
        try {
            ContactRepository::applyFriendLink($request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(null, '提交成功，请等待审核');
    }
}