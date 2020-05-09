<?php
namespace Module\Family\Service;

use Module\Family\Domain\Model\ClanModel;

class HomeController extends Controller {
	
	public function indexAction() {
	    $clan_list = ClanModel::query()->page();
		return $this->show(compact('clan_list'));
	}
}