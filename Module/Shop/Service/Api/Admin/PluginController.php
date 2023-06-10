<?php
declare(strict_types=1);
namespace Module\Shop\Service\Api\Admin;

use Module\Shop\Domain\Repositories\Admin\PluginRepository;

class PluginController extends Controller {

    public function indexAction() {
        return $this->renderData(PluginRepository::getList());
    }

    public function toggleAction(string $code) {
        try {
            return $this->render(
                PluginRepository::toggle($code)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

}