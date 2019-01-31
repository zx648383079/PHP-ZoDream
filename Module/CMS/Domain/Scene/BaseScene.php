<?php
namespace Module\CMS\Domain\Scene;

use Module\CMS\Domain\Fields\BaseField;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Database\Schema\Column;
use Zodream\Helpers\Str;

abstract class BaseScene implements SceneInterface {

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


    /**
     * @param $type
     * @return BaseField
     * @throws \Exception
     */
    public static function newField($type) {
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