<?php
declare(strict_types=1);
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\CategoryRepository;
use Module\Blog\Domain\Repositories\CommentRepository;
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
            'categories' => sprintf('%s::%s', CategoryRepository::class, 'localizeGet'),
            'tags' => sprintf('%s::%s', TagRepository::class, 'get'),
            'detail' => sprintf('%s::%s', BlogRepository::class, 'detail'),
            'relation' => sprintf('%s::%s', TagRepository::class, 'getRelationBlogs'),
            'new_comment' => sprintf('%s::%s', CommentRepository::class, 'newList'),
            'new_blog' => sprintf('%s::%s', BlogRepository::class, 'getNew'),
        ]));
    }
}