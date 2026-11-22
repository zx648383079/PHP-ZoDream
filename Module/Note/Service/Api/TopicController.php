<?php
declare(strict_types=1);
namespace Module\Note\Service\Api;

use Module\ModuleController as Controller;
use Module\Note\Domain\Repositories\NoteRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class TopicController extends Controller {

    public function rules() {
        return [
            'index' => '*',
            '*' => '@'
        ];
    }

    public function indexAction(int $id) {
        try {
            $res = NoteRepository::topicGet($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render(
            $res
        );
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,200',
                'description' => 'string:0,255',
                'status' => 'int:0,9'
            ]);
            return $this->render(NoteRepository::topicSave($data));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            NoteRepository::topicRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function batchSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'string:0,200',
                'description' => 'string:0,255',
                'status' => 'int:0,9',
                'items' => ''
            ]);
            NoteRepository::topicBatchSave($data);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}