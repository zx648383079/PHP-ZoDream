<?php
declare(strict_types=1);
namespace Module\Finance\Service\Api;

use Module\Finance\Domain\Repositories\AccountRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class AccountController extends Controller {

    public function indexAction(int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        return $this->renderData(AccountRepository::all());
    }

    public function detailAction(int $id) {
        try {
            $model = AccountRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = AccountRepository::save($request->validate([
                'id' => 'int',
                'name' => 'required|string:0,35',
                'money' => 'numeric',
                'status' => 'int:0,9',
                'frozen_money' => 'numeric',
                'remark' => '',
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        AccountRepository::softDelete($id);
        return $this->renderData(true);
    }

    public function changeAction(int $id) {
        try {
            $model = AccountRepository::change($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }
}