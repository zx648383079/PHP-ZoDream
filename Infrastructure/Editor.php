<?php
declare(strict_types=1);
namespace Infrastructure;

use Zodream\Template\View;

final class Editor {

    public static function markdown(string $name, mixed $content): string {
        return <<<HTML
<textarea id="editor-container" style="height: 400px;" name="{$name}">{$content}</textarea>
HTML;
    }

    public static function html(string $name, mixed $content, array $option = []): string {
        if (!isset($option['height']) || $option['height'] < 100) {
            $option['height'] = 400;
        }
        return config('view.editor') === 'ueditor' ?
            self::ueditor($name, $content, $option) :
            self::editor($name, $content, $option);
    }

    public static function ueditor(string $name, mixed $content, array $option = []): string {
        $id = 'editor_'.substr(md5($name), 0, 6);
        $options = self::getUEditorOptions(isset($option['editor_mode']) && $option['editor_mode'] > 0);
        $js = <<<JS
var ue = UE.getEditor('{$id}', {$options});
JS;
        view()->registerJsFile('/assets/ueditor/ueditor.config.js')
            ->registerJsFile('/assets/ueditor/ueditor.all.js')->registerJs($js);
        return <<<HTML
<script id="{$id}" style="height: {$option['height']}px" name="{$name}" type="text/plain">{$content}</script>
HTML;
    }

    private static function getUEditorOptions(bool $isSimple): string {
        if (!$isSimple) {
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

    public static function editor(string $name, mixed $content, array $option = []): string {
        $id = 'editor_'.substr(md5($name), 0, 6);
        $js = <<<JS
$('#{$id}').editor();
JS;
        view()
            ->registerJsFile('@jquery.editor.min.js')
            ->registerCssFile('@editor.css')
            ->registerJs($js, View::JQUERY_READY);
        return <<<HTML
<script id="{$id}" style="height: {$option['height']}px" name="{$name}" type="text/plain">{$content}</script>
HTML;
    }
}