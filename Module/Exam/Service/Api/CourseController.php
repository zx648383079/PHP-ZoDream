<?php
namespace Module\Exam\Service\Api;

use Module\Exam\Domain\Model\CourseModel;

class CourseController extends Controller {

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