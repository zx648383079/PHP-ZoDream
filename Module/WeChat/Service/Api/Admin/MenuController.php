<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Repositories\MenuRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class MenuController extends Controller {

    public function indexAction() {
        return $this->renderData(
            MenuRepository::getList($this->weChatId())
        );
    }

    public function detailAction(int $id) {
        $model = MenuModel::find($id);
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = MenuRepository::save($this->weChatId(), $request);
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
        MenuRepository::remove($id);
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