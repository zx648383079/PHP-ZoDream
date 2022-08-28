<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\AddressRepository;

class AddressController extends Controller {

    public function indexAction(int $user, string $keywords = '') {
        return $this->renderPage(
            AddressRepository::search($user, $keywords)
        );
    }

}