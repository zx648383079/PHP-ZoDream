<?php
namespace Module\Document\Service;

use Module\Document\Domain\Model\PageModel;
use Module\Document\Domain\Model\ProjectModel;

class PageController extends Controller {

    public function indexAction($id) {
        $model = PageModel::find($id);
        $project = ProjectModel::find($model->project_id);
        if (!$project->canRead()) {
            return $this->redirectWithMessage('./',
                '无权限查看此项目文档');
        }
        $tree_list = PageModel::getTree($model->project_id);
        return $this->show(compact('project', 'tree_list', 'model'));
    }
}