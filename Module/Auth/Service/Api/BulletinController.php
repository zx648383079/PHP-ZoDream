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

    public function indexAction(string $keywords = '', $status = 0) {
        $model_list = BulletinRepository::getList($keywords, $status);
        return $this->renderPage($model_list);
    }

    public function infoAction(int $id) {
        try {
            $model = BulletinRepository::read($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function readAction($id) {
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

}