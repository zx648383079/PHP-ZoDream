<?php
declare(strict_types=1);
namespace Module\Plugin\Service\Admin;

use Module\Plugin\Domain\Repositories\PluginRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '') {
        $items = PluginRepository::getList($keywords);
        return $this->show(compact('items'));
    }

    public function syncAction() {
        try {
            PluginRepository::sync();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }
}