<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\ReplyModel;

use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Template;

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
        })->orderBy('id', 'desc')->page();
        $event_list = $this->event_list;
        return $this->show(compact('reply_list', 'event_list'));
    }

    public function addAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
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
        if (!$model->setEditor()->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        ReplyModel::cacheReply($model->wid, true);
        return $this->jsonSuccess([
            'url' => $this->getUrl('reply')
        ]);
    }

    public function deleteAction($id) {
        ReplyModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function allAction($user_id = 0) {
        $user_list = FansModel::query()->alias('f')
            ->where('f.status', FansModel::STATUS_SUBSCRIBED)
            ->leftJoin(UserModel::tableName().' u', 'f.id', 'u.id')
            ->whereNotNull('u.id')
            ->get('f.id,u.nickname as name');
        return $this->show(compact('user_id', 'user_list'));
    }

    public function sendAllAction() {

    }

    public function templateAction() {
        $model_list = TemplateModel::where('wid', $this->weChatId())->page();
        return $this->show(compact('model_list'));
    }

    public function refreshTemplateAction() {
        /** @var Template $api */
        $api = WeChatModel::find($this->weChatId())
            ->sdk(Template::class);
        $data = $api->allTemplate();
        if (!isset($data['template_list'])) {
            return $this->jsonFailure('同步失败');
        }
        TemplateModel::where('wid', $this->weChatId())->delete();
        foreach ($data['template_list'] as $item) {
            TemplateModel::create([
                'wid' => $this->weChatId(),
                'template_id' => $item['template_id'],
                'title' => $item['title'],
                'content' => $item['content'],
                'example' => $item['example'],
            ]);
        }
        return $this->jsonSuccess([
            'refresh' => true
        ]);
    }

    public function templateFieldAction($id) {
        $model = TemplateModel::where('template_id', $id)->first();
        if (empty($model)) {
            return $this->jsonFailure('模板不存在');
        }
        return $this->jsonSuccess($model->getFields());
    }

    public function templatePreviewAction($id, $data) {
        $model = TemplateModel::where('template_id', $id)->first();
        if (empty($model)) {
            return $this->jsonFailure('模板不存在');
        }
        return $this->jsonSuccess($model->preview($data));
    }
}