<?php
declare(strict_types=1);
namespace Module\Book\Service\Api;

use Module\Book\Domain\Repositories\BangRepository;
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
            'recommend_bang' => sprintf('%s::%s', BangRepository::class, 'recommend'),
            'click_bang' => sprintf('%s::%s', BangRepository::class, 'click'),
            'hot_bang' => sprintf('%s::%s', BangRepository::class, 'hot'),
            'new_bang' => sprintf('%s::%s', BangRepository::class, 'newRecommend'),
            'week_bang' => sprintf('%s::%s', BangRepository::class, 'weekClick'),
            'size_bang' => sprintf('%s::%s', BangRepository::class, 'size'),
            'over_bang' => sprintf('%s::%s', BangRepository::class, 'over'),
        ]));
    }
}