<?php
declare(strict_types=1);
namespace Infrastructure;

use Zodream\Template\View;
use Zodream\Template\ViewFactory;

final class Editor {

    public static function markdown(string $name, mixed $content): string {
        return <<<HTML
<textarea id="editor-container" style="height: 400px;" name="{$name}">{$content}</textarea>
HTML;
    }

    private static function isUEditor(): bool {
        return config('view.editor') === 'ueditor';
    }

    public static function register(View|ViewFactory $provider): void {
        if (self::isUEditor()) {
            $provider->registerJsFile(['/assets/ueditor/ueditor.config.js',
                '/assets/ueditor/ueditor.all.js']);
        } else {
            $provider->registerJsFile('@jquery.editor.min.js')
                ->registerCssFile('@editor.min.css');
        }
    }

    public static function render(View|ViewFactory|null $provider, string $name, mixed $content,
                                  array $option = []): string {
        if (!isset($option['height']) || $option['height'] < 100) {
            $option['height'] = 400;
        }
        $id = 'editor_'.substr(md5($name), 0, 6);
        if (self::isUEditor()) {
            $options = self::getUEditorOptions(isset($option['editor_mode']) && $option['editor_mode'] > 0);
            $js = <<<JS
UE.delEditor('{$id}');
UE.getEditor('{$id}', {$options});
JS;
        } else {
            $js = <<<JS
$('#{$id}').editor();
JS;
        }
        if (!empty($provider)) {
            $provider->registerJs($js, View::JQUERY_READY);
            $js = '';
        }

        return <<<HTML
<textarea id="{$id}" style="height: {$option['height']}px" name="{$name}">{$content}</textarea>
{$js}
HTML;
    }

    public static function html(string $name, mixed $content, array $option = []): string {
        $provider = \view();
        self::register($provider);
        return self::render($provider, $name, $content, $option);
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

}