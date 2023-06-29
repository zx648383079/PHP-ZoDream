<?php
declare(strict_types=1);
namespace Module\Document\Service\Api\Member;

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
            $data = $input->validate([
                'id' => 'int',
                'name' => 'required|string:0,35',
                'type' => 'int:0,10',
                'project_id' => 'required|int',
                'parent_id' => 'int',
                'content' => '',
                'version_id' => 'int',
            ]);
            $model = PageRepository::save($data);
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