<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\EditorInput;
use Module\WeChat\Domain\Repositories\FollowRepository;
use Module\WeChat\Domain\Repositories\ReplyRepository;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ReplyController extends Controller {

    public function indexAction(string $event = '') {
        try {
            return $this->renderPage(
                ReplyRepository::getList($this->weChatId(), $event)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ReplyRepository::getSelf($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
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
            $model = ReplyRepository::save($this->weChatId(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function updateAction(int $id, array $data) {
        try {
            $model = ReplyRepository::update($id, $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            ReplyRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function allAction(string $keywords = '') {
        try {
            return $this->renderData(
                FollowRepository::searchFans($this->weChatId(), $keywords)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function sendAllAction(int $user_id = 0, array $editor = []) {
        try {
            ReplyRepository::send($this->weChatId(), $user_id, $editor);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function templateAction() {
        try {
            return $this->renderPage(
                ReplyRepository::templateList($this->weChatId())
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function refreshTemplateAction() {
        try {
            ReplyRepository::asyncTemplate($this->weChatId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function templateFieldAction(string $id) {
        try {
            return $this->renderData(
                ReplyRepository::template($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function templateDetailAction(string $id) {
        try {
            return $this->render(
                ReplyRepository::templateDetail($id)
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