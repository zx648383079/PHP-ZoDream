<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\ModelRepository;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Template\View;

class Model extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($field, 'option'), [
            'model' => 0
        ]);
        if ($isJson) {
            return [
                [
                    'name' => 'linkage_id',
                    'label' => '模型',
                    'type' => 'select',
                    'value' => $option['model'],
                    'items' => ModelRepository::all(0)
                ],
            ];
        }
        $model_list = ModelModel::where('type', 0)->pluck('name', 'id');
        return implode('', [
            Theme::select('setting[option][model]', $model_list, $option['model'], '模型')
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->uint()->default(0);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'model',
                'value' => $value
            ];
        }
        $option = static::fieldSetting($field,'option');
//        $model = ModelModel::find($option['model']);
//        $items = empty($model) ? [] : CMSRepository::scene()->setModel($model)
//            ->query()->where('model_id', $model->id)->pluck('title', 'id');
//        return Theme::select($field->field, $items, $value, $field->name, $field->is_required > 0);
        $value = intval($value);
        $url = url('./@admin/content/search', ['model' => intval($option['model'])]);
        $js = <<<JS
$('#model-{$field['id']}').multiSelect({
    default: {$value},
    data: '{$url}',
    tag: '{$field['field']}',
    name: 'title',
    searchable: true,
    multiLevel: false
});
JS;

        view()->registerCssFile('@dialog-select.min.css')
            ->registerJsFile('@jquery.min.js')
            ->registerJsFile('@jquery.multi-select.min.js')
            ->registerJs($js, View::JQUERY_READY);
        return <<<HTML
<div class="input-group">
    <label for="{$field['field']}">{$field['name']}</label>
    <div id="model-{$field['id']}">
    
    </div>
</div>
HTML;
    }

    public function toText(mixed $value, ModelFieldModel|array $field): string {
        $option = static::fieldSetting($field,'option');
        $model = FuncHelper::model(intval($option['model']));
        // TODO 临时使用注意事项
        return (string)CMSRepository::scene()->setModel($model)->query()->where('id', $value)->value('title');
    }
}