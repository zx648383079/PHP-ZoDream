<?php
namespace Module\Auth\Service\Api;

use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    protected function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {
        return $this->render('auth');
    }



}