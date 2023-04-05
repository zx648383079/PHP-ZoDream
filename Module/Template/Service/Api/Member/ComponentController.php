<?php
declare(strict_types=1);
namespace Module\Template\Service\Api\Member;

use Module\Template\Domain\Repositories\ComponentRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ComponentController extends Controller {

    public function indexAction(string $keywords = '', int $type = 0, int $category = 0) {
        return $this->renderPage(
            ComponentRepository::selfList($keywords, $type, $category)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ComponentRepository::selfGet($id)
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
                'description' => 'string:0,200',
                'thumb' => 'string:0,100',
                'cat_id' => 'required|int',
                'price' => 'int',
                'type' => 'int:0,127',
                'author' => 'string:0,20',
                'version' => 'string:0,10',
                'status' => 'int:0,127',
                'path' => 'required|string:0,200',
            ]);
            return $this->render(
                ComponentRepository::selfSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            ComponentRepository::selfRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function importAction(Input $input) {
        try {
            ComponentRepository::selfImport($input->file('file'));
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}