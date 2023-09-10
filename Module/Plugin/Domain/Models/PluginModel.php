<?php
namespace Module\Plugin\Domain\Models;


use Module\Plugin\Domain\Entities\PluginEntity;
use Zodream\Helpers\Json;

/**
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $author
 * @property string $version
 * @property string $path
 * @property integer $status
 * @property string $configs
 * @property integer $updated_at
 * @property integer $created_at
 */
class PluginModel extends PluginEntity {


    public function getConfigsAttribute(): array {
        $value = $this->getAttributeSource('configs');
        return empty($value) ? [] : Json::decode($value);
    }

    public function setConfigsAttribute(mixed $value): void {
        if (empty($value)) {
            $value = '';
        } elseif (is_array($value)) {
            $value = Json::encode($value);
        }
        $this->setAttributeSource('configs', $value);
    }
}