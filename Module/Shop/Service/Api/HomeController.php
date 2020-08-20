<?php
namespace Module\Shop\Service\Api;

use Module\Shop\Domain\Repositories\ShopRepository;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render(ShopRepository::siteInfo());
    }
}