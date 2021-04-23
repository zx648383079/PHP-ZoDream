<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\CategoryRepository;
use Module\Blog\Domain\Repositories\TagRepository;
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
            'categories' => sprintf('%s::%s', CategoryRepository::class, 'get'),
            'tags' => sprintf('%s::%s', TagRepository::class, 'get'),
            'detail' => sprintf('%s::%s', BlogRepository::class, 'detail'),
        ]));
    }
}