<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ContentModel;
use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Schema\Column;
use Zodream\Html\Dark\Theme;

class File extends BaseField {

    const DEFAULT_ALLOW = '';

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::text('setting[option][allow]', self::DEFAULT_ALLOW, '允许格式'),
            Theme::text('setting[option][length]', '2M', '允许大小'),
        ]);
    }



    public function converterField(Column $column, ModelFieldModel $field) {
        $column->varchar($field->length > 10 ? $field->length : 255)->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        $option = $field->setting('option');
        return Theme::file($field->field, $value, $field->name, null,
            $field->is_required > 0)->options([
            'allow' => $option && isset($option['allow']) ? $option['allow'] : self::DEFAULT_ALLOW
        ]);
    }
}