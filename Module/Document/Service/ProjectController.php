<?php
declare(strict_types=1);
namespace Module\Document\Service;

use Module\Document\Domain\Model\ProjectModel;
use Module\Document\Domain\Repositories\ApiRepository;
use Module\Document\Domain\Repositories\PageRepository;
use Module\Document\Domain\Repositories\ProjectRepository;

class ProjectController extends Controller {

    public function indexAction(int $id, int $version = 0) {
        $project = ProjectRepository::get($id);
        if (!$project->canRead()) {
            return $this->redirectWithMessage('./',
                '无权限查看此项目文档');
        }
        $tree_list = $project->type == ProjectModel::TYPE_API
            ? ApiRepository::tree($id, $version) : PageRepository::tree($id, $version);
        $version_list = ProjectRepository::versionAll($id);
        return $this->show(compact('project', 'tree_list', 'version_list', 'version'));
    }

}