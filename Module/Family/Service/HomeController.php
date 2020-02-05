<?php
namespace Module\Family\Service;

class HomeController extends Controller {
	
	public function indexAction() {
		return $this->show();
	}
}