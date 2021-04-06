<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\ProductRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class ProductController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        return $this->renderData(ProductRepository::all());
    }

    public function detailAction(int $id) {
        try {
            $model = ProductRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = ProductRepository::save($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,50',
                'status' => 'int:0,9',
                'remark' => '',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        ProductRepository::remove($id);
        return $this->renderData(true);
    }

    public function changeAction(int $id) {
        try {
            $model = ProductRepository::change($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}