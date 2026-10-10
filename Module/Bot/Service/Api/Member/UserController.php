<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Member;

use Module\Bot\Domain\Repositories\FollowRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class UserController extends Controller {

    public function indexAction(string $keywords = '', int $group = 0, bool $blacklist = false) {
        try {
            return $this->renderPage(
                FollowRepository::getList($this->botId(), $keywords, $group, $blacklist)
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
            FollowRepository::async($this->botId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        try {
            return $this->renderData(
                FollowRepository::search($this->botId(), $keywords, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function groupSearchAction(string $keywords = '', int|array $id = 0) {
        try {
            return $this->renderData(
                FollowRepository::groupSearch($this->botId(), $keywords, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function groupAction(string $keywords = '') {
        return $this->renderPage(
            FollowRepository::groupList($this->botId(), $keywords)
        );
    }

    public function groupSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
            ]);
            return $this->render(
                FollowRepository::groupSave($this->botId(), $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categoryDeleteAction(int $id) {
        try {
            FollowRepository::groupRemove($this->botId(), $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}