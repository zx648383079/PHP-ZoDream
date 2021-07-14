<?php
declare(strict_types=1);
namespace Module\Exam\Service\Api\Admin;

use Module\Exam\Domain\Repositories\CourseRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class CourseController extends Controller {
    public function indexAction() {
        return $this->renderData(
            CourseRepository::all(true)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                CourseRepository::get($id)
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
                'thumb' => 'string:0,200',
                'description' => 'string:0,200',
                'parent_id' => 'int',
            ]);
            return $this->render(
                CourseRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function deleteAction(int $id) {
        try {
            CourseRepository::remove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function allAction() {
        return $this->renderData(
            CourseRepository::all(false)
        );
    }

    public function searchAction(string $keywords = '', int|array $id = 0) {
        return $this->renderData(CourseRepository::search($keywords, $id));
    }

    public function gradeAllAction(int $course = 0) {
        return $this->renderData(CourseRepository::gradeAll($course));
    }

    public function gradeAction(string $keywords = '', int $course = 0) {
        return $this->renderPage(CourseRepository::gradeList($keywords, $course));
    }

    public function gradeSaveAction(Input $input) {
        try {
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,30',
                'grade' => 'int',
                'course_id' => 'int',
            ]);
            return $this->render(
                CourseRepository::gradeSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function gradeDeleteAction(int $id) {
        try {
            CourseRepository::gradeRemove($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}