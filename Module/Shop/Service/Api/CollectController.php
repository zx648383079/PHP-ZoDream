<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Repositories\CollectRepository;

class CollectController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction() {
        return $this->renderPage(
            CollectRepository::getList()
        );
    }

    public function addAction(int $id) {
        try {
            CollectRepository::add($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function deleteAction(int|array $id) {
        try {
            CollectRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(false);
    }

    public function toggleAction(int $id) {
        try {
            return $this->renderData(
                CollectRepository::toggle($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}