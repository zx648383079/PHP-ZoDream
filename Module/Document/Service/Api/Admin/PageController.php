<?php
declare(strict_types=1);
namespace Module\Document\Service\Api\Admin;

use Module\Document\Domain\Repositories\PageRepository;
use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class PageController extends Controller {

    public function indexAction(int $id) {
        try {
            $model = PageRepository::getSelf($id);
            if (!ProjectRepository::canOpen($model->project_id)) {
                throw new \Exception('无权限浏览');
            }
            return $this->render($model);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $model = PageRepository::save($input->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }


    public function deleteAction(int $id) {
        try {
            $model = PageRepository::getSelf($id);
            ProjectRepository::getSelf($model->project_id);
            PageRepository::removeSelf($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}