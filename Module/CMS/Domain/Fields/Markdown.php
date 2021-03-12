<?php
namespace Module\CMS\Domain\Fields;

use Infrastructure\HtmlExpand;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;


class Markdown extends BaseField {

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::text('setting[option][width]', '', '宽度'),
            Theme::text('setting[option][height]', '', '高度'),
        ]);
    }


    public function converterField(Column $column, ModelFieldModel $field) {
        return $column->mediumtext()->nullable()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        return Theme::textarea($field->field, $value, $field->name, null,
            $field->is_required > 0);
    }

    public function toText($value, ModelFieldModel $field) {
        return HtmlExpand::toHtml($value, true);
    }
}