<?php
declare(strict_types=1);
namespace Module\Plugin\Service\Api\Admin;

use Module\Plugin\Domain\Repositories\PluginRepository;

class HomeController extends Controller {

    public function indexAction(string $keywords = '') {
        return $this->renderPage(PluginRepository::getList($keywords));
    }

    public function syncAction() {
        try {
            PluginRepository::sync();
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true);
    }
}