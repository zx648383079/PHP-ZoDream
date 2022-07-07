<?php
declare(strict_types=1);
namespace Module\OnlineTV\Service\Api;

use Module\OnlineTV\Domain\Repositories\CategoryRepository;
use Module\OnlineTV\Domain\Repositories\MovieRepository;
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
                'categories' => sprintf('%s::%s', CategoryRepository::class, 'levelTree'),
                'areas' => sprintf('%s::%s', MovieRepository::class, 'areaList'),
            ]));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}