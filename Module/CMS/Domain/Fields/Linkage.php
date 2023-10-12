<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Module\CMS\Domain\Repositories\CMSRepository;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;

use Zodream\Infrastructure\Support\MessageBag;
use Zodream\Template\View;

class Linkage extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        $items = LinkageModel::query()->selectRaw('code as id,name')->asArray()->get();
        $linkageId = static::fieldSetting($field, 'option', 'linkage_id');
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



    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->uint()->default(0)->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        $linkageId = static::fieldSetting($field, 'option', 'linkage_id');
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'linkage',
                'value' => $value
            ];
        }
        $value = intval($value);
        $url = url('./form/linkage', ['id' => $linkageId, 'lang' => CMSRepository::siteLanguage()]);
        $js = <<<JS
$('#linkage-{$field['id']}').multiSelect({
    default: {$value},
    data: '{$url}',
    tag: '{$field['field']}'
});
JS;

        view()->registerJsFile('@jquery.min.js')
            ->registerJsFile('@jquery.multi-select.min.js')
            ->registerJs($js, View::JQUERY_READY);
        return <<<HTML
<div class="input-group">
    <label for="{$field['field']}">{$field['name']}</label>
    <div id="linkage-{$field['id']}">
    
    </div>
</div>
HTML;

    }

    public function filterInput(mixed $value, ModelFieldModel|array $field, MessageBag $bag): mixed {
        $value = intval($value);
        if ($field['is_required'] && $value < 1) {
            $bag->add($field['field'], sprintf('[%s]%s', $field['name'],
                !empty($field['error_message']) ? $field['error_message'] : '必填项'));
        }
        return $value;
    }

    public function toText($value, ModelFieldModel|array $field): string {
        return (string)LinkageDataModel::where('id', $value)->value('full_name');
    }
}