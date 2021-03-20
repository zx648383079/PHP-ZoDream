<?php
declare(strict_types=1);
namespace Module\Document\Service\Api;

use Module\Document\Domain\Repositories\PageRepository;
use Module\Document\Domain\Repositories\ProjectRepository;

class PageController extends Controller {

    public function indexAction(int $id) {
        try {
            $model = PageRepository::get($id);
            if (!ProjectRepository::canOpen($model->project_id)) {
                throw new \Exception('无权限浏览');
            }
            return $this->render(
                PageRepository::getRead($model)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}