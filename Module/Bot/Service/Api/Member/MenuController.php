<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Member;

use Module\Bot\Domain\Repositories\MenuRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class MenuController extends Controller {

    public function indexAction() {
        try {
            return $this->renderData(
                MenuRepository::getManageList($this->botId())
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function detailAction(int $id) {
        try {
            return $this->render(MenuRepository::getSelf($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Request $request) {
        try {
            $data = $request->validate([
                'name' => 'required|string:0,100',
                'type' => 'int:0,127',
                'content' => 'string:0,500',
                'parent_id' => 'int',
            ]);
            $model = MenuRepository::save($this->botId(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function batchSaveAction(array $data) {
        try {
            MenuRepository::batchSave($this->botId(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(MenuRepository::getList($this->botId()));
    }

    public function deleteAction(int $id) {
        try {
            MenuRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    /**
     * 应用
     * @throws \Exception
     */
    public function applyAction() {
        try {
            MenuRepository::async($this->botId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}