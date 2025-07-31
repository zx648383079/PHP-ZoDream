<?php
declare(strict_types=1);
namespace Module\Document\Service\Api\Member;

use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class ProjectController extends Controller {

    public function indexAction(string $keywords = '', int $type = 0) {
        return $this->renderPage(
            ProjectRepository::getSelfList($keywords, $type)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ProjectRepository::getSelf($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $model = ProjectRepository::save($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id) {
        try {
            ProjectRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function versionAction(int $id) {
        try {
            ProjectRepository::getSelf($id);
            return $this->renderData(ProjectRepository::versionAll($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }


    public function versionNewAction(int $project, int $version, string $name) {
        try {
            ProjectRepository::createVersion($project, $version, $name);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function versionDeleteAction(int $project, int $version) {
        try {
            ProjectRepository::versionRemove($project, $version);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }

    public function catalogAction(int $id, int $version = 0) {
        try {
            return $this->renderData(
                ProjectRepository::catalog($id, $version)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}