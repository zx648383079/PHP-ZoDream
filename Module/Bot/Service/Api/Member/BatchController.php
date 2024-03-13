<?php
declare(strict_types=1);
namespace Module\Bot\Service\Api\Member;

use Module\Bot\Domain\Repositories\ReplyRepository;
use Module\Bot\Domain\Repositories\TemplateRepository;
use Zodream\Route\Controller\Concerns\BatchAction;

class BatchController extends Controller {

    use BatchAction;

    public function indexAction() {
        return $this->render($this->invokeBatch([
            'template_type' => sprintf('%s::typeList', TemplateRepository::class),
            'template_category' => sprintf('%s::categoryAll', TemplateRepository::class),
            'scenes' => sprintf('%s::sceneList', ReplyRepository::class)
        ]));
    }

}