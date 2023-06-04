<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Member;

use Module\WeChat\Domain\Repositories\FollowRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class UserController extends Controller {

    public function indexAction(string $keywords = '', int $group = 0, bool $blacklist = false) {
        try {
            return $this->renderPage(
                FollowRepository::getList($this->weChatId(), $keywords, $group, $blacklist)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function updateAction(int $id, array $data) {
        try {
            $model = FollowRepository::update($id, $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function refreshAction() {
        try {
            FollowRepository::async($this->weChatId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        try {
            return $this->renderData(
                FollowRepository::search($this->weChatId(), $keywords, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function groupSearchAction(string $keywords = '', int|array $id = 0) {
        try {
            return $this->renderData(
                FollowRepository::groupSearch($this->weChatId(), $keywords, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function groupAction(string $keywords = '') {
        return $this->renderPage(
            FollowRepository::groupList($this->weChatId(), $keywords)
        );
    }

    public function groupSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
            ]);
            return $this->render(
                FollowRepository::groupSave($this->weChatId(), $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categoryDeleteAction(int $id) {
        try {
            FollowRepository::groupRemove($this->weChatId(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}