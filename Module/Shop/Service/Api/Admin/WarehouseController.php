<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\WarehouseRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class WarehouseController extends Controller {
    public function indexAction(string $keywords = '') {
        return $this->renderPage(
            WarehouseRepository::getList($keywords)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                WarehouseRepository::getWithRegion($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'tel' => 'required|string:0,30',
                'link_user' => 'string:0,30',
                'address' => 'string:0,255',
                'longitude' => 'string:0,50',
                'latitude' => 'string:0,50',
                'remark' => 'string:0,255',
                'region' => '',
            ]);
            return $this->render(
                WarehouseRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            WarehouseRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function goodsAction(string $keywords = '', int $warehouse = 0, int $goods = 0, int $product = 0) {
        return $this->renderPage(WarehouseRepository::goodsList($keywords, $warehouse, $goods, $product));
    }

    public function goodsChangeAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'tel' => 'required|string:0,30',
                'link_user' => 'string:0,30',
                'address' => 'string:0,255',
                'longitude' => 'string:0,50',
                'latitude' => 'string:0,50',
                'remark' => 'string:0,255',
            ]);
            return $this->render(
                WarehouseRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logAction(string $keywords = '', int $warehouse = 0, int $goods = 0, int $product = 0) {
        return $this->renderPage(WarehouseRepository::logList($keywords, $warehouse, $goods, $product));
    }
}