<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\GroupRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class GroupController extends Controller {

    public function indexAction() {
        return $this->renderData(
            GroupRepository::getList()
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                GroupRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
                'type' => 'int:0,9',
                'description' => 'string:0,255',
            ]);
            return $this->render(
                GroupRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            GroupRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}