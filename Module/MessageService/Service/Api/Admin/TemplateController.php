<?php
declare(strict_types=1);
namespace Module\MessageService\Service\Api\Admin;

use Module\MessageService\Domain\Repositories\MessageRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class TemplateController extends Controller {

    public function indexAction(int $type = 0, string $keywords = '') {
        return $this->renderPage(MessageRepository::templateList($keywords, $type));
    }

    public function detailAction(int $id) {
        try {
            return $this->render(MessageRepository::template($id));
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
                'data' => 'required|string:0,255',
                'content' => 'required',
                'target_no' => 'string:0,32',
            ]);
            return $this->render(MessageRepository::templateSave($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int|array $id) {
        try {
            MessageRepository::templateRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}