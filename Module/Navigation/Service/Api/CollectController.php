<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api;

use Zodream\Infrastructure\Contracts\Http\Input;

final class CollectController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction() {
        return $this->renderData();
    }


    public function saveAction(Input $input) {
        try {
            $data = $input->validate([

            ]);
            return $this->render();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {

        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function clearAction() {
        try {

        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function resetAction() {
        try {
            return $this->renderData();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}