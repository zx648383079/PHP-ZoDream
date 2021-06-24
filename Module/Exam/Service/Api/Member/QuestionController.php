<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Member;


use Module\Exam\Domain\Repositories\QuestionRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class QuestionController extends Controller {

    public function indexAction(string $keywords = '', int $course = 0) {
        return $this->renderPage(
            QuestionRepository::selfList($keywords, $course)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                QuestionRepository::selfFull($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'title' => 'required|string:0,255',
                'image' => 'string:0,200',
                'course_id' => 'required|int',
                'material_id' => 'int',
                'type' => 'int:0,127',
                'easiness' => 'int:0,10',
                'content' => '',
                'dynamic' => '',
                'answer' => '',
                'analysis_items' => '',
                'option_items' => '',
            ]);
            return $this->render(
                QuestionRepository::selfSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            QuestionRepository::selfRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}