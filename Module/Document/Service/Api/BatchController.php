<?php
declare(strict_types=1);
namespace Module\Document\Service\Api;

use Module\Document\Domain\Repositories\ProjectRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {
    use BatchAction;

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction() {
        try {
            return $this->render($this->invokeBatch([
                'project' => sprintf('%s::%s', ProjectRepository::class, 'get'),
                'version' => sprintf('%s::%s', ProjectRepository::class, 'versionAll'),
                'catalog' => sprintf('%s@%s', ProjectController::class, 'catalogAction'),
                'page' => sprintf('%s@%s', ProjectController::class, 'pageAction'),
                'language' => sprintf('%s@%s', ApiController::class, 'languageAction'),
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}