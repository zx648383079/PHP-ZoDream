<?php
declare(strict_types=1);
namespace Module\SEO\Service\Api;

class HomeController extends Controller {

    public function rules() {
        return [
            '*' => '*'
        ];
    }

    public function indexAction() {
        return $this->render([
            'name' => 'PHP-ZoDream',
            'version' => '5.0',
            'logo' => url()->asset('assets/images/favicon.png'),
        ]);
    }

}