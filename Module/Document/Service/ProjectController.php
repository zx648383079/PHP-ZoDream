<?php
namespace Module\Document\Service;

use Module\Document\Domain\Model\ApiModel;
use Module\Document\Domain\Model\ProjectModel;

class ProjectController extends Controller {

    public function indexAction($id) {
        $project = ProjectModel::find($id);
        if (!$project->canRead()) {
            return $this->redirectWithMessage('./',
                '无权限查看此项目文档');
        }
        $tree_list = $project->type == ProjectModel::TYPE_API ? ApiModel::getTree($id) : PageModel::getTree($id);
        return $this->show(compact('project', 'tree_list'));
    }

}