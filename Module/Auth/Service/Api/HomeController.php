<?php
namespace Module\Auth\Service\Api;

use Zodream\Route\Controller\RestController;

class HomeController extends RestController {

    protected function rules() {
        return [
            'index' => ['POST'],
        ];
    }

    public function indexAction() {

    }



}