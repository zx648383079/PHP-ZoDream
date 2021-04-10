<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\CategoryRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CategoryController extends Controller {

    public function indexAction(int $site) {
        return $this->renderData(
            CategoryRepository::getList($site)
        );
    }

    public function detailAction(int $site, int $id) {
        try {
            return $this->render(
                CategoryRepository::get($site, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(int $site, Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,100',
                'title' => 'required|string:0,100',
                'type' => 'int:0,9',
                'model_id' => 'int',
                'parent_id' => 'int',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'image' => 'string:0,100',
                'thumb' => 'string:0,100',
                'content' => '',
                'url' => 'string:0,100',
                'position' => 'int:0,999',
                'groups' => '',
                'category_template' => 'string:0,20',
                'list_template' => 'string:0,20',
                'show_template' => 'string:0,20',
                'setting' => '',
            ]);
            return $this->render(
                CategoryRepository::save($site, $data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $site, int $id) {
        try {
            CategoryRepository::remove($site, $id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function allAction(int $site) {
        return $this->renderData(
            CategoryRepository::all($site)
        );
    }
}