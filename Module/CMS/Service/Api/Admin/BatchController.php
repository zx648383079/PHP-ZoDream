<?php
declare(strict_types=1);
namespace Module\CMS\Service\Api\Admin;

use Module\CMS\Domain\Repositories\CategoryRepository;
use Module\CMS\Domain\Repositories\GroupRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {

    use BatchAction;

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'category' => sprintf('%s::%s', CategoryRepository::class, 'all'),
            'group' => sprintf('%s::%s', GroupRepository::class, 'getList'),
            'model' => sprintf('%s::%s', ModelRepository::class, 'all'),
            'field_type' => sprintf('%s::%s', ModelRepository::class, 'fieldType'),
            'model_tab' => sprintf('%s::%s', ModelRepository::class, 'fieldTab'),
        ]));
    }

}