<?php
declare(strict_types=1);
namespace Module\Catering\Service;

class HomeController extends Controller {
	
	public function indexAction() {
		return $this->show();
	}


}