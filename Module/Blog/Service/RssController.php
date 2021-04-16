<?php
declare(strict_types=1);
namespace Module\Blog\Service;

use Module\Blog\Domain\Repositories\RssRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Contracts\Http\Output;

class RssController extends ModuleController {

    public function indexAction(Output $response) {
        return $response->xml(
            RssRepository::renderOrCache()
        );
    }

}