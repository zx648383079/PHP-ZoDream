<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Fields;

use Module\CMS\Domain\Model\ModelFieldModel;
use Zodream\Database\Contracts\Column;
use Zodream\Html\Dark\Theme;


class Editor extends BaseField {

    public function options(ModelFieldModel $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                [
                    'name' => 'width',
                    'label' => '宽度',
                    'type' => 'text',
                    'value' => '',
                ],
                [
                    'name' => 'height',
                    'label' => '高度',
                    'type' => 'text',
                    'value' => '',
                ],
                [
                    'name' => 'is_mb_auto',
                    'label' => '移动端自动宽度',
                    'type' => 'switch',
                    'value' => '',
                ],
                [
                    'name' => 'editor_mode',
                    'label' => '编辑器模式',
                    'type' => 'radio',
                    'value' => 0,
                    'items' => ['完整版', '精简版', '优化版']
                ],
            ];
        }
        return implode('', [
            Theme::text('setting[option][width]', '', '宽度'),
            Theme::text('setting[option][height]', '', '高度'),
            Theme::radio('setting[option][is_mb_auto]', ['是', '否'], 0, '移动端自动宽度'),
            Theme::radio('setting[option][editor_mode]', ['完整版', '精简版', '优化版'], 0, '编辑器模式'),
        ]);
    }


    public function converterField(Column $column, ModelFieldModel $field): void {
        $column->mediumtext()->nullable()->comment($field->name);
    }

    public function toInput($value, ModelFieldModel|array $field, bool $isJson = false): array|string {
        if ($isJson) {
            return [
                'name' => $field['field'],
                'label' => $field['name'],
                'type' => 'editor',
                'value' => $value
            ];
        }
        $options = $this->getEditorOptions();
        $id = 'editor_'.$field['id'];
        $js = <<<JS
var ue = UE.getEditor('{$id}', {$options});
JS;
        view()->registerJsFile('/assets/ueditor/ueditor.config.js')
            ->registerJsFile('/assets/ueditor/ueditor.all.js')->registerJs($js);
        return <<<HTML
<div>{$field['name']}</div>
<script id="{$id}" style="height: 400px" name="{$field['field']}" type="text/plain">
    {$value}
</script>
HTML;

    }

    private function getEditorOptions(int $mode = 0): string {
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