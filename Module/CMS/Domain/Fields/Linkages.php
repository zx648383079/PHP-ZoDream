<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Template\View;

class Linkages extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                [
                    'name' => 'linkage_id',
                    'label' => '联动项',
                    'type' => 'select',
                    'value' => 0,
                    'items' => LinkageModel::query()->get()
                ],
            ];
        }
        return implode('', [
            Theme::select('setting[option][linkage_id]', [LinkageModel::query()->get()], 0, '联动项'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->string()->default('')->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        $linkageId = static::fieldSetting($field, 'option', 'linkage_id');
        $items = $this->getItems($value);
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'linkages',
                'value' => $items
            ];
        }
        $url = url('./form/linkage', ['id' => $linkageId]);
        $js = <<<JS
$('#linkage-{$field['id']}').multiSelect({
    data: '{$url}',
    tag: 'add_{$field['field']}'
});
JS;

        view()->registerJsFile('@jquery.min.js')
            ->registerJsFile('@jquery.multi-select.min.js')
            ->registerJs($js, View::JQUERY_READY);

        $html = '';
        foreach ($items as $item) {
            $html .= <<<HTML
<div class="selected-item">
    <span class="item-close">&times;</span>
    <div class="item-label">{$item['name']}</div>
    <input type="hidden" name="{$field['field']}[]" value="{$item['id']}">
</div>
HTML;
        }

        return <<<HTML
<div class="input-group">
    <label for="{$field['field']}">{$field['name']}</label>
     <div class="multi-input-container">
        <div class="selected-container">
            {$html}
        </div>

        <div class="add-container">
            <div id="linkage-{$field['id']}">
        
            </div>
            <div class="btn btn-primary add-item">添加</div>
        </div>
    </div>
</div>
HTML;

    }

    public function toText($value, ModelFieldModel $field): string {
        $items = static::fromMultipleValue($value);
        if (empty($items)) {
            return '';
        }
        return implode(',', LinkageDataModel::whereIn('id', $items)
            ->pluck('full_name'));
    }

    public function filterInput(mixed $value, ModelFieldModel|array $field, MessageBag $bag): mixed {
        return static::toMultipleValue($value);
    }

    public function getItems(mixed $value): array {
        $items = static::fromMultipleValue($value);
        if (empty($items)) {
            return [];
        }
        return LinkageDataModel::whereIn('id', $items)->get();
    }


}