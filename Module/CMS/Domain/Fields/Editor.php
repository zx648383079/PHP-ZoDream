<?php
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;


class Editor extends BaseField {

    public function options(ModelFieldModel $field) {
        return implode('', [
            Theme::text('setting[option][width]', '', '宽度'),
            Theme::text('setting[option][height]', '', '高度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], 0, '移动端自动宽度'),
            Theme::radio('setting[option][editor_mode]', ['完整版', '精简版', '优化版'], 0, '编辑器模式'),
        ]);
    }


    public function converterField(Column $column, ModelFieldModel $field) {
        return $column->mediumtext()->nullable()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel $field) {
        $options = $this->getEditorOptions();
        $id = 'editor_'.$field->id;
        $js = <<<JS
var ue = UE.getEditor('{$id}', {$options});
JS;
        view()->registerJsFile('/assets/ueditor/ueditor.config.js')
            ->registerJsFile('/assets/ueditor/ueditor.all.js')->registerJs($js);
        return <<<HTML
<div>{$field->name}</div>
<script id="{$id}" style="height: 400px" name="{$field->field}" type="text/plain">
    {$value}
</script>
HTML;

    }

    private function getEditorOptions($mode = 0) {
        if ($mode < 1) {
            return '{}';
        }
        return json_encode([
            'toolbars' => [
                [
                    'fullscreen',
                    'source',
                    'undo',
                    'redo',
                    'bold',
                    'italic',
                    'underline',
                    'customstyle',
                    'link',
                    'simpleupload',
                    'insertvideo',
                ]
            ]
        ]);
    }
}