<?php
declare(strict_types=1);
namespace Module\Family\Service;

class BookController extends Controller {
	
	public function indexAction() {
		return $this->show();
	}
}