<?php
namespace Module\OpenPlatform\Domain\Concerns;


use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformOptionModel;
use Module\OpenPlatform\Module;

trait EditPlatformOption {

    public function optionAction() {
        $url = preg_replace('#option[^/]*#', '', url()->current());
        $items = PlatformModel::findWidthAuth();
        return $this->renderFile(Module::viewFile('Option/index.php'), compact('items', 'url'));
    }

    public function editOptionAction($platform_id) {
        $this->layout = false;
        $platform = PlatformModel::findWithAuth($platform_id);
        if (empty($platform)) {
            return '';
        }
        $data = PlatformOptionModel::getStores($platform_id, $this->platformOption());
        return $this->renderFile(Module::viewFile('Option/edit.php'), compact('data'));
    }

    public function saveOptionAction($platform_id, $option) {
        PlatformOptionModel::saveOption($platform_id, $option);
        return $this->jsonSuccess(null, '保存成功');
    }
}