<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\BlogRepository;
use Zodream\Route\Controller\RestController;


class ArchivesController extends RestController {

    public function indexAction() {
        return $this->renderData(BlogRepository::getArchives());
    }
}