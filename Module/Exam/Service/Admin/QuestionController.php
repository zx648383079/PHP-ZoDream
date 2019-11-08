<?php
namespace Module\Exam\Service\Admin;

use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\QuestionAnswerModel;
use Module\Exam\Domain\Model\QuestionModel;
use Module\Exam\Domain\Model\QuestionOptionModel;

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
        if (!$model->isNewRecord) {
            $option_list = QuestionOptionModel::where('question_id', $model->id)
                ->orderBy('id', 'asc')->get();
        }
        return $this->show(compact('model', 'cat_list', 'option_list'));
    }

    public function optionAction($id, $type = 0) {
        $model = QuestionModel::findOrNew($id);
        $option_list = [];
        $model->type = intval($type);
        if (!$model->isNewRecord && $model->type < 2) {
            $option_list = QuestionOptionModel::where('question_id', $model->id)
                ->orderBy('id', 'asc')->get();
        }
        $this->layout = false;
        return $this->show(compact('model', 'option_list'));
    }

    public function saveAction() {
        $model = new QuestionModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->jsonFailure($model->getFirstError());
        }
        QuestionOptionModel::batchSave($model,
            app('request')->get('option', []));
        return $this->jsonSuccess([
            'url' => $this->getUrl('question')
        ]);

    }

    public function deleteAction($id) {
        QuestionModel::where('id', $id)->delete();
        QuestionAnswerModel::where('question_id', $id)->delete();
        QuestionOptionModel::where('question_id', $id)->delete();
        return $this->jsonSuccess([
           'url' => $this->getUrl('question')
        ]);
    }
}