<?php
namespace Module\WeChat\Service\Admin;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Model\ReplyModel;
use Module\WeChat\Domain\Repositories\FollowRepository;
use Module\WeChat\Domain\Repositories\ReplyRepository;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ReplyController extends Controller {


    public function rules() {
        return [
            '*' => 'w'
        ];
    }

    public function indexAction(string $event = '') {
        $reply_list = ReplyRepository::getList($this->weChatId(), $event);
        $event_list = ReplyRepository::eventItems();
        return $this->show(compact('reply_list', 'event_list'));
    }

    public function addAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = ReplyModel::findOrNew($id);
        $request = request();
        if ($request->has('type')) {
            $this->layout = false;
            $model->type = $request->get('type');
            return $this->show('/Admin/layouts/editor', compact('model'));
        }
        $event_list = ReplyRepository::eventItems();
        return $this->show('edit', compact('model', 'event_list'));
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'id' => 'int',
                'event' => 'required|string:0,20',
                'keywords' => 'string:0,60',
                'match' => 'int:0,127',
                'content' => 'required',
                'type' => 'int:0,127',
                'status' => 'int:0,127',
            ]);
            ReplyRepository::save($this->weChatId(), $data);
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
            ReplyRepository::send($this->weChatId(), empty($user_id) ? 0 : 2, $user_id, $editor);
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