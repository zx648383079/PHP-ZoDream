<?php
namespace Module\OpenPlatform\Domain\Concerns;


use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformOptionModel;

/**
 * rest 编辑配置
 * @package Module\OpenPlatform\Domain\Concerns
 */
trait ApiPlatformOption {

    public function platformAction() {
        $items = PlatformModel::findWidthAuth();
        return $this->renderData($items);
    }

    public function optionAction(int $platform) {
        $model = PlatformModel::findWithAuth($platform);
        if (empty($model)) {
            return $this->renderFailure('不存在');
        }
        $data = PlatformOptionModel::getStores($platform, $this->platformOption());
        return $this->renderData($data);
    }

    public function optionSaveAction(int $platform, array $option) {
        PlatformOptionModel::saveOption($platform_id, $option);
        return $this->renderData(true);
    }
}