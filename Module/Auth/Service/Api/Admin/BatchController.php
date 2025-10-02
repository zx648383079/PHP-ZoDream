<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\RoleRepository;
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
            'roles' => sprintf('%s::%s', RoleRepository::class, 'all'),
            'zones' => sprintf('%s::%s', ZoneRepository::class, 'all'),
        ]));
    }
}