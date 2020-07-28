<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\TermRepository;
use Zodream\Route\Controller\RestController;


class TermController extends RestController {

    public function indexAction() {
        return $this->renderData(TermRepository::get());
    }
}