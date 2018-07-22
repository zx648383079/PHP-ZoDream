<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\ReplyModel;
use Zodream\Infrastructure\Http\URL;
use Zodream\ThirdParty\WeChat\EventEnum;

class ReplyController extends Controller {

    protected $event_list = [
        'default' => '默认回复',
        EventEnum::Message => '消息',
        EventEnum::Subscribe => '关注',
        EventEnum::Click => '菜单事件',
    ];

    protected function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction($event = null) {
        $reply_list = ReplyModel::where('wid', $this->weChatId())
            ->when(!empty($event), function ($query) use ($event) {
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
        $model->wid = $this->weChatId();
        $model->load();
        if ($model->event != EventEnum::Message) {
            $model->keywords = null;
        }
        if ($model->setEditor()->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('reply')
            ]);
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