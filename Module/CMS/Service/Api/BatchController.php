<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api;

use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {
    use BatchAction;

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'category' => sprintf('%s@%s', CategoryController::class, 'indexAction'),
            'content' => sprintf('%s@%s', ContentController::class, 'indexAction'),
            'form' => sprintf('%s@%s', FormController::class, 'indexAction'),
        ]));
    }
}