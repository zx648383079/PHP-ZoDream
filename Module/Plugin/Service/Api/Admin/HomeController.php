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

    public function installAction(int $id, array $data = []) {
        try {
            $res = PluginRepository::install($id, $data);
            return $this->renderData(is_array($res) ? $res : true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function uninstallAction(int|array $id) {
        try {
            PluginRepository::uninstall($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData(true);
    }

    public function executeAction(int $id) {
        try {
            return $this->renderData(PluginRepository::execute($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }
}