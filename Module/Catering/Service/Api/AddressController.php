<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api;

use Module\Catering\Domain\Repositories\AddressRepository;

class AddressController extends Controller {

    public function rules() {
        return [
            '*' => '@'
        ];
    }

	public function indexAction() {
        return $this->renderPage(AddressRepository::getList());
	}
}