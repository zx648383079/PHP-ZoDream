<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\UserRepository;
use Module\Auth\Domain\Repositories\ZoneRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class UserController extends Controller {

    public function indexAction(string $keywords = '', string $sort = 'id', string|bool|int $order = 'desc') {
        return $this->renderPage(UserRepository::getAll($keywords, $sort, $order));
    }

    public function profileAction(int $id) {
        try {
            $model = UserRepository::get($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction(int $id) {
        try {
            $model = UserRepository::manageDetail($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function saveAction(Request $request) {
        try {
            $model = UserRepository::save($request->validate([
                'id' => 'int',
                'name' => 'required|string',
                'email' => 'required|email',
                'sex' => 'int',
                'avatar' => 'string',
                'birthday' => 'string',
                'password' => 'string',
                'confirm_password' => 'string',
            ]), $request->get('roles', []));
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        $zoneId = intval($request->get('zone_id'));
        if ($zoneId > 0) {
            ZoneRepository::userChange($model->id, $zoneId);
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            UserRepository::remove($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function verifyAction(int $id, string $id_card = '') {
        try {
            UserRepository::saveIDCard($id, $id_card);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function searchAction(string $keywords = '') {
        return $this->renderPage(
            UserRepository::searchUser($keywords)
        );
    }
}