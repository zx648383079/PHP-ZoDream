<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Admin;

use Module\Bot\Domain\Repositories\TemplateRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class TemplateController extends Controller {

    public function indexAction(string $keywords = '', int $type = 0, int $category = 0) {
        return $this->renderPage(
            TemplateRepository::getList($keywords, $type, $category)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                TemplateRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'type' => 'int:0,999',
                'cat_id' => 'int',
                'name' => 'required|string:0,100',
                'content' => 'required',
            ]);
            return $this->render(
                TemplateRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            TemplateRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function categoryAction(string $keywords = '') {
        return $this->renderPage(
            TemplateRepository::categoryList($keywords)
        );
    }

    public function categorySaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,20',
                'parent_id' => 'int',
            ]);
            return $this->render(
                TemplateRepository::categorySave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function categoryDeleteAction(int $id) {
        try {
            TemplateRepository::categoryRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}