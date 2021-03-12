<?php
namespace Module\Document\Service\Admin;

use Module\Document\Domain\Model\PageModel;
use Module\Document\Domain\Repositories\PageRepository;
use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PageController extends Controller {

    public function indexAction(int $id) {
        try {
            $model = PageRepository::getSelf($id);
            $project = ProjectRepository::getSelf($model->project_id);
            $tree_list = PageRepository::tree($model->project_id, $model->version_id);
            return $this->show(compact('project', 'tree_list', 'model'));
        } catch (\Exception $ex) {
            return $this->redirectWithMessage($this->getUrl(''), $ex->getMessage());
        }
    }

    public function createAction(int $project_id = 0, int $parent_id = 0, int $version_id = 0) {
        return $this->editAction(0, $project_id, $parent_id, $version_id);
    }

    public function editAction(int $id, int $project_id = 0, int $parent_id = 0, int $version_id = 0) {
        $model = $id > 0 ? PageRepository::getSelf($id) : new PageModel(
            compact('project_id', 'parent_id', 'version_id'));
        $project = ProjectRepository::getSelf($model->project_id);
        $tree_list = PageRepository::tree($model->project_id, intval($model->version_id));
        return $this->show('edit', compact('model', 'project', 'tree_list'));
    }

    public function saveAction(Input $input) {
        try {
            $model = PageRepository::save($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        if ($model->parent_id < 1) {
            return $this->renderData([
                'url' => $this->getUrl('project', [
                    'id' => $model->project_id,
                    'version' => $model->version_id
                ])
            ]);
        }
        return $this->renderData([
            'url' => $this->getUrl('page', ['id' => $model->id])
        ]);
    }


    public function deleteAction(int $id) {
        try {
            $model = PageRepository::getSelf($id);
            ProjectRepository::getSelf($model->project_id);
            PageRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => $this->getUrl('project', [
                'id' => $model->project_id,
                'version' => $model->version_id
            ])
        ]);
    }
}