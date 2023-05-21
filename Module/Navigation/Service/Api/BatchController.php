<?php
declare(strict_types=1);
namespace Module\Navigation\Service\Api;

use Module\Navigation\Domain\Repositories\SiteRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {

    use BatchAction;

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'site_category' => sprintf('%s::%s', SiteRepository::class, 'categories'),
            'site_recommend' => sprintf('%s::%s', SiteRepository::class, 'recommendGroup'),
        ]));
    }

}