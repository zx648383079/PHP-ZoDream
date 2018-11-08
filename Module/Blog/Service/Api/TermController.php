<?php
namespace Module\Blog\Service\Api;

use Module\Blog\Domain\Model\TermModel;
use Zodream\Route\Controller\RestController;


class TermController extends RestController {

    public function indexAction() {
        $term_list = TermModel::all();
        return $this->render(compact('term_list'));
    }
}