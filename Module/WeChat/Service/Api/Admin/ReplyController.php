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
        return $this->renderPage(
            ReplyRepository::getList($this->weChatId(), $event)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ReplyRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Request $request) {
        try {
            $model = ReplyRepository::save($this->weChatId(), $request);
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
        return $this->renderData(
            FollowRepository::searchFans($this->weChatId(), $keywords)
        );
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
        return $this->renderPage(
            ReplyRepository::templateList($this->weChatId())
        );
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