<?php
namespace Module\Exam\Service;


use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;
use Module\Exam\Domain\Repositories\PagerRepository;

class PageController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

    public function indexAction(int $id) {
        try {
            $pager = PagerRepository::createOrThrow(0, 0, $id);
        } catch (\Exception $ex) {
            return $this->redirect('./');
        }
        $evaluate = PageEvaluateModel::where('user_id', auth()->id())->where('id', $id)
            ->first();
        if (empty($evaluate)) {
            return $this->redirect('./');
        }
        $page = PageModel::find($evaluate->page_id);
        $question_list = PageQuestionModel::with('question')
            ->where('evaluate_id', $evaluate->id)->orderBy('id', 'asc')->get();
        $items = [];
        foreach ($question_list as $i => $item) {
            $args = $item->format($i + 1, true);
            $args['id'] = $item->id;
            $args['your_answer'] = $item->answer;
            $args['right'] = $item->status == PageQuestionModel::STATUS_SUCCESS ? 1 : -1;
            $items[] = $args;
        }
        return $this->show(compact('page', 'evaluate', 'question_list', 'items'));
    }

    public function saveAction(array $question) {
        foreach ($question as $id => $item) {
            $model = PageQuestionModel::where('user_id', auth()->id())
                ->where('id', $id)->first();
            $model->answer = $item['answer'];
            $model->save();
        }
        return $this->renderData(true);
    }

    public function checkAction(int $id) {
        /** @var PageEvaluateModel $model */
        $model = PageEvaluateModel::where('user_id', auth()->id())->where('id', $id)->first();
        if ($model->status > 0) {
            return $this->renderData([
                'url' => url('./')
            ], '交卷成功！');
        }
        $model->status = 1;
        $model->spent_time = $model->getSpentTime();
        $question_list = PageQuestionModel::with('question')
            ->where('evaluate_id', $model->id)->orderBy('id', 'asc')->get();
        foreach ($question_list as $item) {
            $right = $item->question->check($item->answer, $item->content);
            $item->status =
                 $right
                    ? PageQuestionModel::STATUS_SUCCESS : PageQuestionModel::STATUS_FAILURE;
            if ($right) {
                $model->right ++;
                $model->score ++;
            } else {
                $model->wrong ++;
            }
            $item->save();
        }
        $model->save();
        return $this->renderData([
            'url' => url('./')
        ], '交卷成功！');
    }

    public function historyAction(int $id) {
        $evaluate = PageEvaluateModel::where('user_id', auth()->id())->where('id', $id)
            ->first();
        if (empty($evaluate)) {
            return $this->redirect('./');
        }
        $page = PageModel::find($evaluate->page_id);
        $question_list = PageQuestionModel::with('question')
            ->where('evaluate_id', $evaluate->id)->orderBy('id', 'asc')->get();
        $items = [];
        foreach ($question_list as $i => $item) {
            $args = $item->format($i + 1, true);
            $args['id'] = $item->id;
            $args['your_answer'] = $item->answer;
            $args['right'] = $item->status == PageQuestionModel::STATUS_SUCCESS ? 1 : -1;
            $items[] = $args;
        }
        return $this->show(compact('page', 'evaluate', 'question_list', 'items'));
    }
}