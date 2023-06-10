<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\AttributeRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class AttributeController extends Controller {

    public function indexAction(int $group_id, string $keywords = '') {
        return $this->renderPage(AttributeRepository::getList($group_id, $keywords));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                AttributeRepository::get($id)
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
                'group_id' => 'required|int',
                'type' => 'int:0,127',
                'search_type' => 'int:0,127',
                'input_type' => 'int:0,127',
                'default_value' => 'string:0,255',
                'position' => 'int',
                'property_group' => 'string:0,20',
            ]);
            return $this->render(
                AttributeRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            AttributeRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }


    public function groupAction(string $keywords = '') {
        return $this->renderPage(
            AttributeRepository::groupList($keywords)
        );
    }

    public function detailGroupAction(int $id) {
        try {
            return $this->render(
                AttributeRepository::groupGet($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveGroupAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'property_groups' => 'string:0,255',
            ]);
            return $this->render(
                AttributeRepository::groupSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteGroupAction(int $id) {
        try {
            AttributeRepository::groupRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function groupAllAction() {
        return $this->renderData(
            AttributeRepository::groupAll()
        );
    }
}