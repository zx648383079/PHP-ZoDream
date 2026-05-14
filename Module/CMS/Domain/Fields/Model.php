<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\ModelModel;
use Module\CMS\Domain\Repositories\ModelRepository;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Template\View;

class Model extends BaseField {

    public function options(bool $isJson = false): array|string {
        $option = static::filterData(static::fieldSetting($this->field, 'option'), [
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



    public function alterColumn(Column $column): void {
        $column->uint()->default(0);
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'model',
                'value' => $value
            ];
        }
        $option = static::fieldSetting($this->field,'option');
//        $model = ModelModel::find($option['model']);
//        $items = empty($model) ? [] : $this->context->scene()->setModel($model)
//            ->query()->where('model_id', $model->id)->pluck('title', 'id');
//        return Theme::select($this->field->field, $items, $value, $this->field->name, $this->field->is_required > 0);
        $value = intval($value);
        $url = url('./@admin/content/search', ['model' => intval($option['model'])]);
        $js = <<<JS
$('#model-{$this->field['id']}').multiSelect({
    default: {$value},
    data: '{$url}',
    tag: '{$this->controlName()}',
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
    <label for="{$this->controlName()}">{$this->controlLabel()}</label>
    <div id="model-{$this->field['id']}">
    
    </div>
</div>
HTML;
    }

    public function toText(mixed $value): string {
        $option = static::fieldSetting($this->field,'option');
        $model = FuncHelper::model(intval($option['model']));
        // TODO 临时使用注意事项
        return (string)$this->context->scene()->setModel($model)->query()->where('id', $value)->value('title');
    }
}