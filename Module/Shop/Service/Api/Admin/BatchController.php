<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\BrandRepository;
use Module\Shop\Domain\Repositories\Admin\CategoryRepository;
use Module\Shop\Domain\Repositories\AttributeRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {

    use BatchAction;

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'category' => sprintf('%s::%s', CategoryRepository::class, 'all'),
            'brand' => sprintf('%s::%s', BrandRepository::class, 'all'),
            'group' => sprintf('%s::%s', AttributeRepository::class, 'groupAll'),
        ]));
    }

}