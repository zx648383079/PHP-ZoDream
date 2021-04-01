<?php
declare(strict_types=1);
namespace Module\Chat\Service;

class HomeController extends Controller {

    public $layout = 'main';

    public function indexAction() {
        $user = auth()->user();
        return $this->show(compact('user'));
    }

}