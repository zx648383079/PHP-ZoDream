<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Template\View;

class Linkages extends BaseField {

    public function options(bool $isJson = false): array|string {
        $items = LinkageModel::query()->selectRaw('code as id,name')->asArray()->get();
        $linkageId = static::fieldSetting($this->field, 'option', 'linkage_id');
        if ($isJson) {
            return [
                [
                    'name' => 'linkage_id',
                    'label' => '联动项',
                    'type' => 'select',
                    'value' => $linkageId,
                    'items' => $items
                ],
            ];
        }
        return implode('', [
            Theme::select('setting[option][linkage_id]', [$items], $linkageId, '联动项'),
        ]);
    }



    public function alterColumn(Column $column): void {
        $column->string()->default('')->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        $linkageId = static::fieldSetting($this->field, 'option', 'linkage_id');
        if (empty($linkageId)) {
            return $this->inputTooltip('联动菜单参数配置错误', $isJson);
        }
        $items = $this->getItems($value);
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'linkages',
                'value' => $items
            ];
        }
        $url = url('./form/linkage', ['id' => $linkageId, 'lang' => $this->context->language()]);
        $js = <<<JS
$('#linkage-{$this->field['id']}').multiSelect({
    data: '{$url}',
    tag: 'add_{$this->controlName()}'
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
    <input type="hidden" name="{$this->controlName()}[]" value="{$item['id']}">
</div>
HTML;
        }

        return <<<HTML
<div class="input-group">
    <label for="{$this->controlName()}">{$this->controlLabel()}</label>
     <div class="multi-input-container">
        <div class="selected-container">
            {$html}
        </div>

        <div class="add-container">
            <div id="linkage-{$this->field['id']}">
        
            </div>
            <div class="btn btn-primary add-item">添加</div>
        </div>
    </div>
</div>
HTML;

    }

    public function toText(mixed $value): string {
        $items = static::fromMultipleValue($value);
        if (empty($items)) {
            return '';
        }
        return implode(',', LinkageDataModel::whereIn('id', $items)
            ->pluck('full_name'));
    }

    public function filterInput(mixed $value, MessageBag $bag): mixed {
        $value = static::toMultipleValue($value);
        if ($this->isRequired() && empty($value)) {
            $bag->add($this->controlName(), sprintf('[%s]%s', $this->controlLabel(),
                !empty($this->field['error_message']) ? $this->field['error_message'] : '必填项'));
        }
        return $value;
    }

    public function getItems(mixed $value): array {
        $items = static::fromMultipleValue($value);
        if (empty($items)) {
            return [];
        }
        return LinkageDataModel::whereIn('id', $items)->get();
    }


}