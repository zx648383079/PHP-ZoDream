<?php
namespace Module\Bot\Service\Admin;

use Module\Bot\Domain\Model\EditorTemplateModel;
use Module\Bot\Domain\Repositories\TemplateRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class TemplateController extends Controller {
    public function indexAction(int $type = 0) {
        if (request()->isAjax()) {
            $this->layout = false;
        }
        $model_list = TemplateRepository::getList($type);
        return $this->show(request()->isAjax() ? 'page' : 'index', compact('model_list'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = EditorTemplateModel::findOrNew($id);
        return $this->show('edit', compact('model'));
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
            TemplateRepository::save($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('template')
        ]);
    }

    public function deleteAction(int $id) {
        TemplateRepository::remove($id);
        return $this->renderData([
            'refresh' => true
        ]);
    }
}