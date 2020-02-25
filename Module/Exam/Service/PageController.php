<?php
namespace Module\Exam\Service;


use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;

class PageController extends Controller {

    public function indexAction($id) {
        $page = PageModel::find($id);
        $evaluate = $page->createQuestion(auth()->id());
        if (empty($evaluate)) {
            return $this->redirect('./');
        }
        $question_list = PageQuestionModel::with('question')
            ->where('evaluate_id', $evaluate->id)->orderBy('id', 'asc')->get();
        return $this->show(compact('page', 'evaluate', 'question_list'));
    }

    public function saveAction($id, $answer) {
        $model = PageQuestionModel::where('user_id', auth()->id())
            ->where('id', $id)->first();
        $model->answer = $answer;
        return $this->jsonSuccess();
    }

    public function checkAction($id) {
        $model = PageEvaluateModel::where('user_id', auth()->id)->where('id', $id)->first();
        $model->status = 1;
        return $this->jsonSuccess();
    }

    public function historyAction($id) {
        $evaluate = PageEvaluateModel::where('user_id', auth()->id())->where('id', $id)
            ->first();
        if (empty($evaluate)) {
            return $this->redirect('./');
        }
        $page = PageModel::find($evaluate->page_id);
        $question_list = PageQuestionModel::with('question')
            ->where('evaluate_id', $evaluate->id)->orderBy('id', 'asc')->get();
        return $this->show(compact('page', 'evaluate', 'question_list'));
    }
}