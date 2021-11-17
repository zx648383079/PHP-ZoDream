<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api;

use Module\Navigation\Domain\Repositories\CollectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

final class CollectController extends Controller {

    public function rules() {
        return ['*' => '@'];
    }

    public function indexAction() {
        return $this->renderData(CollectRepository::all());
    }


    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,20',
                'link' => 'required|string:0,255',
                'group_id' => 'int',
                'position' => 'int:0,127',
            ]);
            return $this->render(CollectRepository::save($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            CollectRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function clearAction() {
        try {
            CollectRepository::clear();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function resetAction() {
        try {
            return $this->renderData(CollectRepository::reset());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function groupSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,20',
                'position' => 'int:0,127',
            ]);
            return $this->render(CollectRepository::groupSave($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function groupDeleteAction(int $id) {
        try {
            CollectRepository::groupRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}