<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Repositories\FollowRepository;
use Module\WeChat\Domain\Repositories\ReplyRepository;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\ThirdParty\WeChat\EventEnum;

class ReplyController extends Controller {

    protected $event_list = [
        'default' => '默认回复',
        EventEnum::Message => '消息',
        EventEnum::Subscribe => '关注',
        EventEnum::Click => '菜单事件',
    ];

    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(string $event = '') {
        $reply_list = ReplyRepository::getList($this->weChatId(), $event);
        $event_list = $this->event_list;
        return $this->show(compact('reply_list', 'event_list'));
    }

    public function addAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = ReplyModel::findOrNew($id);
        $request = request();
        if ($request->has('type')) {
            $this->layout = false;
            $model->type = $request->get('type');
            return $this->show('/Admin/layouts/editor', compact('model'));
        }
        $event_list = $this->event_list;
        return $this->show('edit', compact('model', 'event_list'));
    }

    public function saveAction(Request $request) {
        try {
            ReplyRepository::save($this->weChatId(), $request);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('reply')
        ]);
    }

    public function deleteAction(int $id) {
        ReplyRepository::remove($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function allAction(int $user_id = 0) {
        $user_list = FollowRepository::searchFans($this->weChatId());
        return $this->show(compact('user_id', 'user_list'));
    }

    public function sendAllAction(int $user_id = 0, array $editor = []) {
        try {
            ReplyRepository::send($this->weChatId(), $user_id, $editor);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData('', '发送成功');
    }


    public function templateAction() {
        $model_list = ReplyRepository::templateList($this->weChatId());
        return $this->show(compact('model_list'));
    }

    public function refreshTemplateAction() {
        try {
            ReplyRepository::asyncTemplate($this->weChatId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function templateFieldAction($id) {
        try {
            return $this->renderData(
                ReplyRepository::template($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function templatePreviewAction(string $id, array $data) {
        try {
            return $this->renderData(
                ReplyRepository::templatePreview($id, $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
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
                Str::studly($action).config('app.action'),
                $request, $this);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}