<?php
declare(strict_types=1);
namespace Service\Account;

use Module\Auth\Domain\Repositories\StatisticsRepository;

class HomeController extends Controller {
	public function indexAction() {
        $items = StatisticsRepository::userCount(auth()->id());
		return $this->show(compact('items'));
	}


}