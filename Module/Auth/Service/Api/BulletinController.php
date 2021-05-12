<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\BulletinRepository;

class BulletinController extends Controller {

    public function rules() {
        return [
            '*' => '@',
        ];
    }

    public function indexAction(string $keywords = '', int $status = 0, int $user = 0, int $last_id = 0) {
        return $this->renderPage(BulletinRepository::getList($keywords, $status, $user, $last_id));
    }

    public function userAction() {
        return $this->renderData(
            BulletinRepository::userList()
        );
    }

    public function infoAction(int $id) {
        try {
            $model = BulletinRepository::read($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function readAction(int $id) {
        try {
            BulletinRepository::read($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function readAllAction() {
        BulletinRepository::readAll();;
        return $this->renderData(true);
    }

    public function deleteAction(int $id) {
        try {
            BulletinRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteUserAction(int $id) {
        try {
            BulletinRepository::removeUser($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

}