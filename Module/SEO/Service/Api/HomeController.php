<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api;

use Module\SEO\Domain\Repositories\OptionRepository;

class HomeController extends Controller {

    public function indexAction() {
        return $this->render(array_merge([
            'name' => 'PHP-ZoDream',
            'version' => '5.0',
            'logo' => url()->asset('assets/images/favicon.png'),
        ], OptionRepository::getOpenList()));
    }

}