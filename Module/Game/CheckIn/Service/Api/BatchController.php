<?php
declare(strict_types=1);
namespace Module\Game\CheckIn\Service\Api;

use Module\Game\CheckIn\Domain\Repositories\CheckinRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {
    use BatchAction;

    public function rules() {
        return [
            'index' => '@',
        ];
    }

    public function methods() {
        return [
            'index' => 'POST'
        ];
    }

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'today' => sprintf('%s::%s', CheckinRepository::class, 'today'),
            'month' => sprintf('%s::%s', CheckinRepository::class, 'monthLog'),
        ]));
    }
}