<?php
namespace Module\Document\Service;

use Module\Document\Domain\Repositories\PageRepository;
use Module\Document\Domain\Repositories\ProjectRepository;

class PageController extends Controller {

    public function indexAction(int $id) {
        $model = PageRepository::get($id);
        $project = ProjectRepository::get($model->project_id);
        if (!$project->canRead()) {
            return $this->redirectWithMessage('./',
                '无权限查看此项目文档');
        }
        $tree_list = PageRepository::tree($model->project_id, $model->version_id);
        return $this->show(compact('project', 'tree_list', 'model'));
    }
}