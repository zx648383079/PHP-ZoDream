<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api\Admin;

use Module\Auth\Domain\Repositories\RoleRepository;
use Module\Auth\Domain\Advanced\ZoneRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {
    use BatchAction;

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction() {
        $target = [
            'roles' => sprintf('%s::%s', RoleRepository::class, 'all'),
        ];
        if (AUTH_ADVANCED) {
            $target['zones'] = sprintf('%s::%s', ZoneRepository::class, 'all');
        }
        return $this->render($this->invokeBatch($target));
    }
}