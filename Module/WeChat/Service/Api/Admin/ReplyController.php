<?php
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\FansModel;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Model\TemplateModel;
use Module\WeChat\Domain\Model\UserModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\ThirdParty\WeChat\EventEnum;
use Zodream\ThirdParty\WeChat\Mass;
use Zodream\ThirdParty\WeChat\Template;

class ReplyController extends Controller {

    protected $event_list = [
        'default' => '默认回复',
        EventEnum::Message => '消息',
        EventEnum::Subscribe => '关注',
        EventEnum::Click => '菜单事件',
    ];

    public function indexAction($event = null) {
        $reply_list = ReplyModel::where('wid', $this->weChatId())
            ->when(!empty($event), function ($query) use ($event) {
            $query->where('event', $event);
        })->orderBy('id', 'desc')->page();
        return $this->renderPage($reply_list);
    }

    public function detailAction($id) {
        $model = ReplyModel::find($id);
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        $model = new ReplyModel();
        $model->wid = $this->weChatId();
        $model->load();
        if ($model->event != EventEnum::Message) {
            $model->keywords = null;
        }
        try {
            EditorInput::save($model, $request);
            if (!$model->autoIsNew()->save()) {
                return $this->renderFailure($model->getFirstError());
            }
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        ReplyModel::cacheReply($model->wid, true);
        return $this->render($model);
    }

    public function deleteAction($id) {
        ReplyModel::where('id', $id)->delete();
        return $this->renderData(true);
    }

    public function allAction($user_id = 0) {
        $user_list = FansModel::query()->alias('f')
            ->where('f.status', FansModel::STATUS_SUBSCRIBED)
            ->leftJoin(UserModel::tableName().' u', 'f.id', 'u.id')
            ->whereNotNull('u.id')
            ->get('f.id,u.nickname as name');
        return $this->renderData($user_list);
    }

    public function sendAllAction($user_id = 0, $editor = []) {
        if ($editor['type'] === 3) {
            return $this->sendTemplate($user_id, $editor['template_id'], $editor['template_url'], $editor['template_data']);
        }
        $data = '';
        $type = Mass::TEXT;
        if ($editor['type'] < 1) {
            $data = $editor['text'];
        }
        /** @var Mass $api */
        $api = WeChatModel::find($this->weChatId())
            ->sdk(Mass::class);
        $openid = null;
        if ($user_id > 0) {
            $openid = UserModel::where('id', $user_id)->value('openid');
        }
        $res = empty($openid) ? $api->sendAll($data, $type) : $api->send([$openid], $data, $type);
        return $this->renderData(true);
    }

    private function sendTemplate($user_id, $template_id, $url, $data) {
        if ($user_id < 1) {
            return $this->renderFailure('模板消息只能发给单个用户');
        }
        $openid = UserModel::where('id', $user_id)->value('openid');
        if (empty($openid)) {
            return $this->renderFailure('用户未关注公众号');
        }
        /** @var Template $api */
        $api = WeChatModel::find($this->weChatId())
            ->sdk(Template::class);
        $res = $api->send($openid, $template_id, url($url), TemplateModel::strToArr($data));
        if ($res) {
            return $this->renderData(true);
        }
        return $this->renderFailure('发送失败');
    }

    public function templateAction() {
        $model_list = TemplateModel::where('wid', $this->weChatId())->page();
        return $this->renderPage($model_list);
    }

    public function refreshTemplateAction() {
        /** @var Template $api */
        $api = WeChatModel::find($this->weChatId())
            ->sdk(Template::class);
        $data = $api->allTemplate();
        if (!isset($data['template_list'])) {
            return $this->renderFailure('同步失败');
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
        return $this->renderData(true);
    }

    public function templateFieldAction($id) {
        $model = TemplateModel::where('template_id', $id)->first();
        if (empty($model)) {
            return $this->renderFailure('模板不存在');
        }
        return $this->renderData($model->getFields());
    }

    public function templatePreviewAction($id, $data) {
        $model = TemplateModel::where('template_id', $id)->first();
        if (empty($model)) {
            return $this->renderFailure('模板不存在');
        }
        return $this->renderData($model->preview($data));
    }

    /**
     * 调用方法
     * @param $type
     * @param $action
     * @param Request $request
     * @throws \Exception
     */
    public function editorAction($type, $action, Request $request) {
        try {
            return EditorInput::invoke($type,
                $this->getActionName(Str::studly($action)),
                $request, $this);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}