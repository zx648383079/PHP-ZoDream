<?php
namespace Module\Chat\Service;

use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\MessageModel;

class MessageController extends Controller {

    public function indexAction() {
    }

    public function pingAction($time = null) {
        $message = MessageModel::where('receive_id', auth()->id())
            ->when(!empty($time), function ($query) use ($time) {
                $query->where('created_at', '>', intval($time));
            })
            ->where('status', MessageModel::STATUS_NONE)->count();
        $apply = ApplyModel::where('user_id', auth()->id())
            ->when(!empty($time), function ($query) use ($time) {
                $query->where('created_at', '>', intval($time));
            })->where('status', 0)->count();
        $time = time();
        return $this->jsonSuccess(compact('message', 'apply', 'time'));
    }

}