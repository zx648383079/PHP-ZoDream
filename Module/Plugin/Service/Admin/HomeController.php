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

    public function installAction(int $id) {
        try {
            $data = PluginRepository::install($id, $_POST);
           return $this->renderData(is_array($data) ? [
               'form' => implode('', $data)
           ] : [
               'refresh' => true
           ]);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }

    public function uninstallAction(int $id) {
        try {
            PluginRepository::uninstall($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function executeAction(int $id) {
        try {
            return $this->renderData(PluginRepository::execute($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex);
        }
    }
}