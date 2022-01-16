<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Repositories\MenuRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class MenuController extends Controller {

    public function indexAction() {
        try {
            return $this->renderData(
                MenuRepository::getList($this->weChatId())
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
            $model = MenuRepository::save($this->weChatId(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function batchSaveAction(array $data) {
        try {
            MenuRepository::batchSave($this->weChatId(), $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(MenuRepository::getList($this->weChatId()));
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
            MenuRepository::async($this->weChatId());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}