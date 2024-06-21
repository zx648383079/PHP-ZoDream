<?php
declare(strict_types=1);
namespace Module\MessageService\Service\Admin;

use Module\MessageService\Domain\Entities\TemplateEntity;
use Module\MessageService\Domain\Repositories\MessageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class TemplateController extends Controller {

    public function indexAction(int $type = 0, string $keywords = '') {
        $model_list = MessageRepository::templateList($keywords, $type);
        return $this->show(compact('model_list'));
    }

    public function editAction(int $id = 0) {
        try {
            $model = $id > 0 ? MessageRepository::template($id) : new TemplateEntity();
            return $this->show(compact('model'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,100',
                'name' => 'required|string:0,20',
                'type' => 'int:0,127',
                'data' => '',
                'content' => 'required|string',
                'target_no' => 'string:0,32',
                'status' => 'int:0,10'
            ]);
            MessageRepository::templateSave($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('template')
        ]);
    }

    public function deleteAction(int|array $id) {
        try {
            MessageRepository::templateRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function toggleAction(int $id) {
        try {
            MessageRepository::templateChange($id, ['status']);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}