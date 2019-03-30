<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\LinkageModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Schema\Column;
use Zodream\Html\Dark\Theme;
use Zodream\Service\Factory;
use Zodream\Template\View;

class Linkage extends BaseField {

    public function options(ModelFieldModel $field) {
        ;
        return implode('', [
            Theme::select('setting[option][linkage_id]', [LinkageModel::query()->all()], 0, '联动项'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->int()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        $value = intval($value);
        $url = url('./admin/linkage/tree', ['id' => $field->setting('option', 'linkage_id')]);
        $js = <<<JS
$('#linkage-{$field->id}').multiSelect({
    default: {$value},
    data: '{$url}',
    tag: '{$field->field}'
});
JS;

        Factory::view()->registerJsFile('@jquery.multi-select.min.js')
            ->registerJs($js, View::JQUERY_READY);
        return <<<HTML
<div class="input-group">
    <label for="{$field->field}">地区</label>
    <div id="linkage-{$field->id}">
    
    </div>
</div>
HTML;

    }
}