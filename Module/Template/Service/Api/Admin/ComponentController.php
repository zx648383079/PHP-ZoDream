<?php
declare(strict_types=1);
namespace Module\Template\Service\Api\Admin;

use Module\Template\Domain\Repositories\ComponentRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ComponentController extends Controller {

    public function indexAction(string $keywords = '', int $user = 0, int $category = 0) {
        return $this->renderPage(
            ComponentRepository::getManageList($keywords, $user, $category)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ComponentRepository::manageGet($id)
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
                ComponentRepository::manageSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(array|int $id) {
        try {
            ComponentRepository::manageRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function reviewAction(array|int $id, array $data) {
        try {
            ComponentRepository::manageReview($id, $data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function uploadAction(Input $input) {
        try {
            return $this->render(ComponentRepository::manageUpload($input->file('file')));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}