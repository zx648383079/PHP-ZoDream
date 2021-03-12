<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\ProjectModel;
use Module\Document\Domain\Repositories\ApiRepository;
use Module\Document\Domain\Repositories\PageRepository;
use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;


class ProjectController extends Controller {

    public function indexAction(int $id, int $version = 0) {
        $project = ProjectModel::findWithAuth($id);
        if (empty($project)) {
            return $this->redirect($this->getUrl(''));
        }
        $tree_list = $project->type == ProjectModel::TYPE_API ?
            ApiRepository::tree($id, $version) : PageRepository::tree($id, $version);
        $version_list = ProjectRepository::versionAll($id);
        return $this->show(compact('project', 'tree_list', 'version_list', 'version'));
    }

    public function createAction() {
        return $this->editAction(0);
    }

    public function editAction(int $id) {
        $model = $id > 0 ? ProjectRepository::getSelf($id) : new ProjectModel(['type' => 1]);
        $project_list = ProjectRepository::allSelf();
        return $this->show('edit', compact('model', 'project_list'));
    }

    public function saveAction(Input $input) {
        try {
            $model = ProjectRepository::save($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('project', ['id' => $model->id])
        ]);
    }

    public function deleteAction(int $id) {
        try {
            ProjectRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('')
        ]);
    }

    public function versionNewAction(int $project, int $version, string $name) {
        try {
            $version = ProjectRepository::createVersion($project, $version, $name);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('project', [
                'id' => $project,
                'version' => $version->id
            ])
        ]);
    }
}