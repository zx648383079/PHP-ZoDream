<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Template\View;

class Linkage extends BaseField {

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
        $column->uint()->default(0)->comment($this->controlLabel());
    }

    public function toInput(mixed $value, bool $isJson = false): array|string {
        $linkageId = static::fieldSetting($this->field, 'option', 'linkage_id');
        if (empty($linkageId)) {
            return $this->inputTooltip('联动菜单参数配置错误', $isJson);
        }
        if ($isJson) {
            return [
                'name' => $this->controlName(),
                'label' => $this->controlLabel(),
                'type' => 'linkage',
                'value' => $value
            ];
        }
        $value = intval($value);
        $url = url('./form/linkage', ['id' => $linkageId, 'lang' => $this->context->language()]);
        $js = <<<JS
$('#linkage-{$this->field['id']}').multiSelect({
    default: {$value},
    data: '{$url}',
    tag: '{$this->controlName()}'
});
JS;

        view()->registerJsFile('@jquery.min.js')
            ->registerJsFile('@jquery.multi-select.min.js')
            ->registerJs($js, View::JQUERY_READY);
        return <<<HTML
<div class="input-group">
    <label for="{$this->controlName()}">{$this->controlLabel()}</label>
    <div id="linkage-{$this->field['id']}">
    
    </div>
</div>
HTML;

    }

    public function filterInput(mixed $value, MessageBag $bag): mixed {
        $value = intval($value);
        if ($this->isRequired() && $value < 1) {
            $bag->add($this->controlName(), sprintf('[%s]%s', $this->controlLabel(),
                !empty($this->field['error_message']) ? $this->field['error_message'] : '必填项'));
        }
        return $value;
    }

    public function toText(mixed $value): string {
        return (string)LinkageDataModel::where('id', $value)->value('full_name');
    }
}