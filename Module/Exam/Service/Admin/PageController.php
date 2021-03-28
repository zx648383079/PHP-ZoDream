<?php
namespace Module\Exam\Service\Admin;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Exam\Domain\Model\CourseModel;
use Module\Exam\Domain\Model\PageEvaluateModel;
use Module\Exam\Domain\Model\PageModel;
use Module\Exam\Domain\Model\PageQuestionModel;
use Module\Exam\Domain\Repositories\PageRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class PageController extends Controller {

    public function indexAction(string $keywords = '') {
        $model_list = PageRepository::getList($keywords);
        return $this->show(compact('model_list', 'keywords'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction($id) {
        $model = PageModel::findOrNew($id);
        $cat_list = CourseModel::tree()->makeTreeForHtml();
        return $this->show('edit', compact('model', 'cat_list'));
    }

    public function saveAction(Request $request) {
        $model = new PageModel();
        $model->load();
        $model->setRule($request->get('rule'));
        if (!$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->renderData([
            'url' => $this->getUrl('page')
        ]);
    }

    public function deleteAction(int $id) {
        PageRepository::remove($id);
        return $this->renderData([
            'url' => $this->getUrl('page')
        ]);
    }

    public function evaluateAction(int $id, string $keywords = '') {
        $page = PageModel::find($id);
        $model_list = PageRepository::evaluateList($id, $keywords);
        return $this->show(compact('model_list', 'page', 'keywords'));
    }

    public function questionAction($id) {
        $evaluate = PageEvaluateModel::find($id);
        $page = PageModel::find($evaluate->page_id);
        $question_list = PageQuestionModel::with('question')->page();
        return $this->show(compact('evaluate', 'question_list', 'page'));
    }
}