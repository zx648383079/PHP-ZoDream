<?php
namespace Module\Short\Service;

use Zodream\Service\Factory;

class HomeController extends Controller {

    protected function rules() {
        return [
            '*' => '@'
        ];
    }

	public function indexAction() {
		return $this->show();
	}

    public function findLayoutFile() {
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }

}