<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Repositories\TermRepository;
use Zodream\Route\Controller\RestController;


class TermController extends RestController {

    public function indexAction() {
        $term_list = TermRepository::get();
        return $this->render($term_list);
    }
}