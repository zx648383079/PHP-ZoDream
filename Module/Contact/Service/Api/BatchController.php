<?php
declare(strict_types=1);
namespace Module\Contact\Service\Api;

use Module\Auth\Service\Api\Controller;
use Module\Contact\Domain\Repositories\ContactRepository;
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
            'friend_link' => sprintf('%s::%s', ContactRepository::class, 'friendLink'),
        ]));
    }
}