<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Repositories\EmojiRepository;
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
            'configs' => sprintf('%s@%s', HomeController::class, 'indexAction'),
            'emoji' => sprintf('%s::%s', EmojiRepository::class, 'all'),
            'agreement' => sprintf('%s@%s', AgreementController::class, 'indexAction'),
        ]));
    }
}