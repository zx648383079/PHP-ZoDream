<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Module;
use Zodream\Database\Schema\Column;
use Zodream\Html\Dark\Theme;

class Model extends BaseField {

    public function options(ModelFieldModel $field) {
        $model_list = ModelModel::where('type', 0)->pluck('name', 'id');
        $option = $field->setting('option');
        return implode('', [
            Theme::select('setting[option][model]', $model_list, isset($option['model']) ? $option['model'] : 0, '模型')
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->int();
    }

    public function toInput($value, ModelFieldModel $field) {
        $option = $field->setting('option');
        $model = ModelModel::find($option['model']);
        $items = Module::scene()->setModel($model)
            ->query()->where('model_id', $model->id)->pluck('title', 'id');
        return Theme::select($field->field, $items, $value, $field->name, $field->is_required > 0);
    }

    public function toText($value, ModelFieldModel $field) {
        $option = $field->setting('option');
        $model = ModelModel::find($option['model']);
        return Module::scene()->setModel($model)->query()->where('id', $value)->value('title');
    }
}