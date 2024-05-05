<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Admin;

use Module\Exam\Domain\Repositories\QuestionRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class QuestionController extends Controller {
    public function indexAction(string $keywords = '', int $course = 0, int $user = 0,
                                int $grade = 0, int $material = 0, bool $filter = false) {
        return $this->renderPage(
            QuestionRepository::getList($keywords, $course, $user, $grade, $material, $filter)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                QuestionRepository::getFull($id)
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
                'course_grade' => 'required|int',
                'material_id' => 'int',
                'status' => 'int',
                'type' => 'int:0,127',
                'easiness' => 'int:0,11',
                'content' => '',
                'dynamic' => '',
                'answer' => '',
                'analysis_items' => '',
                'option_items' => '',
                'children' => '',
            ]);
            return $this->render(
                QuestionRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            QuestionRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function checkAction(string $title, int $id = 0) {
        return $this->renderData(
            QuestionRepository::check($title, $id)
        );
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        return $this->renderData(QuestionRepository::search($keywords, $id));
    }

    public function crawlAction(Input $input) {
        try {
            $data = $input->validate([
                'title' => 'required|string:0,255',
                'image' => 'string:0,200',
                'course_id' => 'required|string',
                'course_grade' => 'required|int',
                'material_id' => 'int',
                'status' => 'int',
                'type' => 'int:0,127',
                'easiness' => 'int:0,11',
                'content' => '',
                'dynamic' => '',
                'answer' => '',
                'analysis_items' => '',
                'option_items' => '',
                'children' => '',
            ]);
            return $this->render(
                QuestionRepository::crawlSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}