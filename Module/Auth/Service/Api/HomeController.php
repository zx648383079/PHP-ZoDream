<?php
namespace Module\Auth\Service\Api;

use Zodream\Route\Attributes\Route;

class HomeController extends Controller {

    public function methods() {
        return [
            'index' => ['POST'],
        ];
    }

    #[Route('open/auth', method: ['GET'])]
    public function indexAction() {
        return $this->render('auth');
    }



}