<?php
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Model\CourseModel;
use Zodream\Route\Controller\RestController;

class CourseController extends RestController {

    public function indexAction($id) {
        $course = CourseModel::find($id);
        return $this->render($course);
    }

    public function childrenAction($id) {
        $data = CourseModel::with('children')
            ->where('parent_id', intval($id))->get();
        return $this->render(compact('data'));
    }

    public function treeAction() {
        $data = CourseModel::tree()->makeTree();
        return $this->render(compact('data'));
    }
}