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

    private static function isJodit(): bool {
        return config('view.editor') === 'jodit';
    }

    public static function register(View|ViewFactory $provider): void {
        if (self::isJodit()) {
            $provider->registerJsFile('@jodit.min.js')
                ->registerCssFile('@jodit.min.css');
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
        if (self::isJodit()) {
            $options = self::getEditorOptions(isset($option['editor_mode']) && $option['editor_mode'] > 0);
            $js = <<<JS
Jodit.make('#{$id}', {$options});
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

    private static function getEditorOptions(bool $isSimple): string {
        $data = [
            'language' => 'zh_cn',
            'uploader' => [
                'url' => url('/ueditor.php', ['action' => 'uploadfile'], false),
            ],
            'cleanHTML' => [
                'denyTags' => 'script,object,embed'
            ]
            // 'iframe' => true,
            // 'iframeBaseUrl' => '/home/map'
        ];
        if ($isSimple) {
            $data['buttons'] = [
                'bold',
                'italic',
                'underline',
                '|',
                'ul',
                'ol',
                '|',
                'font',
                'fontsize',
                'brush',
                '|',
                'image',
                'link',
                '|',
                'align',
                'undo',
                'redo'
            ];
        }
        return json_encode($data);
    }

}