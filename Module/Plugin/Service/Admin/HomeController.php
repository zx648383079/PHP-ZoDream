<?php
declare(strict_types=1);
namespace Module\Plugin\Service\Admin;

use Module\Plugin\Domain\Repositories\PluginRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '') {
        $items = PluginRepository::getList($keywords);
        return $this->show(compact('items'));
    }
}