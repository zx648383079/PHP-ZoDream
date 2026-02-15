<?php
declare(strict_types=1);
namespace Module\Forum\Service\Api\Admin;

use Module\Forum\Domain\Repositories\ForumRepository;
use Module\Auth\Domain\Repositories\ZoneRepository;
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
            'forums' => sprintf('%s::%s', ForumRepository::class, 'all'),
            'zones' => sprintf('%s::%s', ZoneRepository::class, 'all'),
        ]));
    }
}