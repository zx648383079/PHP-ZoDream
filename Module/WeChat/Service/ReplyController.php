<?php
namespace Module\WeChat\Service;

use Module\ModuleController;
use Module\WeChat\Domain\Model\ReplyModel;
use Zodream\ThirdParty\WeChat\EventEnum;

class ReplyController extends ModuleController {

    protected  $event_list = [
        'default' => '默认回复',
        EventEnum::Message => '消息',
        EventEnum::Subscribe => '关注'
    ];

    public function indexAction($event = null) {
        $reply_list = ReplyModel::when(!empty($event), function ($query) use ($event) {
            $query->where('event', $event);
        })->page();
        $event_list = $this->event_list;
        return $this->show(compact('reply_list', 'event_list'));
    }

    public function addAction() {
        return $this->runMethod('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ReplyModel::findOrNew($id);
        $event_list = $this->event_list;
        return $this->show(compact('model', 'event_list'));
    }

    public function saveAction() {
        $model = new ReplyModel();
        $model->wid = '1';
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess($model);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ReplyModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }
}