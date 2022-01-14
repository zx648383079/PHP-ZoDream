<?php
declare(strict_types=1);
namespace Module\WeChat\Service\Api\Admin;

use Module\WeChat\Domain\Repositories\ReplyRepository;
use Module\WeChat\Domain\Repositories\TemplateRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {

    use BatchAction;

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'template_type' => sprintf('%s::typeList', TemplateRepository::class),
            'scenes' => sprintf('%s::sceneList', ReplyRepository::class)
        ]));
    }

}