<?php
declare(strict_types=1);
namespace Module\ResourceStore\Service\Api;

use Module\ResourceStore\Domain\Repositories\CategoryRepository;
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
                'recommend' => sprintf('%s::%s', CategoryRepository::class, 'recommend'),
                'categories' => sprintf('%s::%s', CategoryRepository::class, 'tree'),
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}