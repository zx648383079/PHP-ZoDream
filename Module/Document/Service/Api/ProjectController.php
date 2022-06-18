<?php
declare(strict_types=1);
namespace Module\Document\Service\Api;

use Module\Document\Domain\Repositories\ProjectRepository;

class ProjectController extends Controller {

    public function indexAction(string $keywords = '', int $type = 0) {
        return $this->renderPage(
            ProjectRepository::getList($keywords, $type)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(
                ProjectRepository::get($id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function versionAction(int $id) {
        try {
            if (!ProjectRepository::canOpen($id)) {
                throw new \Exception('无权限浏览');
            }
            return $this->renderData(ProjectRepository::versionAll($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
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

    public function pageAction(int $project, int $id) {
        try {
            return $this->render(
                ProjectRepository::page($project, $id)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function suggestAction(string $keywords) {
        return $this->renderData(ProjectRepository::suggest($keywords));
    }

}