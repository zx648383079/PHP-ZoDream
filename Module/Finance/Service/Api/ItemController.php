<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\ItemRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ItemController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->render(ItemRepository::getList($keywords));
    }


    public function saveAction(Input $request) {
        try {
            $model = ItemRepository::save($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,50',
                'type' => 'int:0,127',
                'remark' => 'string:0,255',
                'status' => 'bool',
                'deleted_at' => ''
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            ItemRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function bindingAction(Input $request, int $log) {
        try {
            $id = intval($request->get('id'));
            if ($id === 0) {
                $model = ItemRepository::save($request->validate([
                    'name' => 'required|string:0,50',
                    'type' => 'int:0,127',
                    'remark' => 'string:0,255',
                ]));
                $id = intval($model['id']);
            }
            ItemRepository::binding($id, $log);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function unbindingAction(int $log) {
        try {
            ItemRepository::unbinding($log);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}