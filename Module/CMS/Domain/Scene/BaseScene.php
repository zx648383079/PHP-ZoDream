<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Fields\BaseField;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Database\Schema\Column;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Traits\ErrorTrait;

abstract class BaseScene implements SceneInterface {

    use ErrorTrait;

    protected $site = 1;

    /**
     * @var ModelModel
     */
    protected $model;

    public function setModel(ModelModel $model, $site = 1) {
        $this->model = $model;
        $this->site = $site;
        return $this;
    }


    public function toInput(ModelFieldModel $field, array $data) {
        if ($field->is_disable > 0) {
            return null;
        }
        return self::newField($field->type)->toInput(isset($data[$field->field])
            ? $data[$field->field] : '', $field);
    }

    /**
     * @param array $data
     * @param ModelFieldModel[] $field_list
     * @return array [main, extend]
     * @throws \Exception
     */
    public function filterInput(array $data, array $field_list) {
        if (empty($field_list)) {
            return [$data, []];
        }
        $extend = $main = [];
        foreach ($field_list as $field) {
            $value = static::newField($field->type)->filterInput(isset($data[$field->field]) ? $data[$field->field]
                : null, $field);
            if ($field->is_main > 0) {
                $main[$field->field] = $value;
                continue;
            }
            $extend[$field->field] = $value;
        }
        return [$main, $extend];
    }

    /**
     * @param $type
     * @return BaseField
     * @throws \Exception
     */
    public static function newField($type) {
        $maps = [
            'switch' => 'SwitchBox',
        ];
        if (isset($maps[$type])) {
            $type = $maps[$type];
        }
        $class = 'Module\CMS\Domain\Fields\\'.Str::studly($type);
        if (class_exists($class)) {
            return new $class;
        }
        throw new \Exception(
            __('Field "{type}" not exist!', compact('type'))
        );
    }

    public static function converterTableField(Column $column, ModelFieldModel $field) {
        static::newField($field->type)->converterField($column, $field);
    }
}