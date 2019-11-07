<?php
namespace Module\Exam\Service\Admin;

use Module\Exam\Domain\Entities\QuestionOptionEntity;
use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\QuestionModel;

class QuestionController extends Controller {

    public function indexAction($keywords = null, $course = 0) {
        $model_list = QuestionModel::with('course')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    QuestionModel::search($query, 'name');
                });
            })->when(!empty($course), function ($query) use ($course) {
                $query->where('course_id', intval($course));
            })->where('parent_id', 0)->orderBy('id', 'desc')->page();
        $cat_list = CourseModel::tree()->makeTreeForHtml();
        return $this->show(compact('model_list', 'cat_list', 'course', 'keywords'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => 0]);
    }

    public function editAction($id) {
        $model = QuestionModel::findOrNew($id);
        $cat_list = CourseModel::tree()->makeTreeForHtml();
        $option_list = [];
        if (!$model->isNewRecord && $model->type < 2) {
            $option_list = QuestionOptionEntity::where('question_id', $model->id)
                ->orderBy('id', 'asc')->get();
        }
        return $this->show(compact('model', 'cat_list', 'option_list'));
    }

    public function saveAction() {


        return $this->jsonSuccess([
            'url' => $this->getUrl('question')
        ]);
    }

    public function deleteAction() {
        return $this->jsonSuccess([
           'url' => $this->getUrl('question')
        ]);
    }
}