<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\TagRepository;
use Zodream\Route\Controller\RestController;


class TagController extends RestController {

    public function indexAction() {
        return $this->renderData(TagRepository::get());
    }
}