<?php
namespace Module\WeChat\Service\Api\Admin;


use Module\WeChat\Domain\Model\MediaTemplateModel;
use Module\WeChat\Domain\Repositories\TemplateRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class TemplateController extends Controller {
    public function indexAction(int $type = 0) {
        return $this->renderPage(
            TemplateRepository::getList($type)
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
                'category' => 'int',
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
}