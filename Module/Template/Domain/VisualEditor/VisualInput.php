<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\SiteWeightModel;
use Zodream\Html\Input;

final class VisualInput {

    /**
     * 设置触发 tab 的元素上
     */
    const TAB_TARGET_KEY = 'data-tab';
    /**
     * 设置响应 tab 进行切换的元素
     */
    const TAB_GROUP_KEY = 'tab';

    public static function title(mixed $value): Input {
        return self::text('title', '标题', $value)->items([
            ['name' => '标题', 'value' => '{$title}'],
            ['name' => '站点名', 'value' => '{$site_title}']
        ]);
    }

    public static function url(): array {
        return [
            self::select('item_type', '链接类型', [
                ['name' => '刷新部件', 'value' => 'target'],
                ['name' => '网址', 'value' => 'url'],
                ['name' => '页面', 'value' => 'page'],
            ], 0)->attr(self::TAB_TARGET_KEY, 'url-type'),
            self::group([
                self::text('item_uri', '部件传值', ''),
                self::select('item_target', '部件id', [], ''),
            ])->attr(self::TAB_GROUP_KEY, 'url-type-target'),
            self::group([
                self::text('item_uri', '网址', ''),
                self::select('item_target', '跳转方式', [
                    ['name' => '当前', 'value' => '_self'],
                    ['name' => '新标签', 'value' => '_blank'],
                ], ''),
            ])->attr(self::TAB_GROUP_KEY, 'url-type-url'),
            self::group([
                self::select('item_id', '跳转页面', [], ''),
                self::select('item_target', '跳转方式', [
                    ['name' => '当前', 'value' => '_self'],
                    ['name' => '新标签', 'value' => '_blank'],
                ], ''),
            ])->attr(self::TAB_GROUP_KEY, 'url-type-page'),
            self::icon('item_icon', '图标', ''),
            self::text('item_title', '标题', ''),
        ];
    }

    public static function basic(SiteWeightModel $model): array {
        return [
            self::title($model->title),
            self::switch('lazy', '懒加载', intval($model->setting('lazy'))),
            self::switch('is_share', '共享', intval($model->is_share)),
            self::align('align', $model->setting('align'))
        ];
    }

    public static function style(SiteWeightModel $model): array {
        return [
            self::group('整体', [
                self::bound('margin', '外边距', ''),
                self::position('position', '布局', ''),
                self::border('border', '边框', []),
                self::color('color', '前景色', ''),
                self::background('background', '背景色', ''),
            ]),
            self::group('标题', [
                self::switch('visibility', '可见', ''),
                self::bound('padding', '内边距', ''),
                self::border('border', '边框', []),
                self::background('background', '背景色', ''),
                self::textAlign('text-align', '对齐', ''),
                self::color('color', '前景色', ''),
                self::size('font-size', '字体大小', 16),
                self::range('font-weight', '字体粗细', 500, 100, 900, 100),
                self::select('font-style', '字体粗细', [], ''),
                self::select('font-family', '字体', [], ''),
            ]),
            self::group('内容', [
                self::switch('visibility', '可见', ''),
                self::bound('padding', '内边距', ''),
                self::border('border', '边框', []),
                self::background('background', '背景色', ''),
                self::textAlign('text-align', '对齐', ''),
                self::color('color', '前景色', ''),
                self::size('font-size', '字体大小', 16),
                self::range('font-weight', '字体粗细', 500, 100, 900, 100),
                self::select('font-style', '字体粗细', [], ''),
                self::select('font-family', '字体', [], ''),
            ]),
        ];
    }

    public static function setting(): array {
        return [
            self::group('主题', [
                self::color('primary', 'Primary Color', ''),
                self::color('primary', 'Secondary Color', ''),
                self::color('primary', 'Light Color', ''),
                self::color('primary', 'Dark Color', ''),
                self::color('primary', 'Muted Color', ''),
                self::color('primary', 'Border Color', ''),
                self::color('primary', 'Info Color', ''),
                self::color('primary', 'Success Color', ''),
                self::color('primary', 'Warning Color', ''),
                self::color('primary', 'Danger Color', ''),
            ]),
            self::group('样式', [
                self::codeEditor('style', '样式', '', 'css')
            ]),
            self::group('脚本', [
                self::codeEditor('script', '脚本', '', 'javascript')
            ])
        ];
    }

    public static function tree(string $name, string $label, array $items, array $input): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'name',  'label', 'items', 'input'));
    }

    public static function images(string $name, string $label, array $items): Input {
        $type = 'images';
        return new Input(compact('type', 'name', 'label', 'items'));
    }

    public static function multiple(string $name, string $label, array $items, array $input): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'name', 'label', 'items', 'input'));
    }

    public static function lazyPage(string $name, $label, mixed $value) {
        return new Input([
            'type' => 'select',
            'search' => url('./'),
            'name' => $name,
            'label' => $label,
            'value' => $value
        ]);
    }

    public static function lazyWeight(string $name, $label, mixed $value) {
        return new Input([
            'type' => 'select',
            'search' => url('./'),
            'name' => $name,
            'label' => $label,
            'value' => $value
        ]);
    }

    public static function editor(string $name, mixed $content): Input {
        return Input::html($name, '内容')->value($content);
    }

    public static function codeEditor(string $name, string $label, mixed $value, string $language = ''): Input {
        return new Input([
            'type' => 'code',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'language' => $language
        ]);
    }

    public static function text(string $name, string $label, mixed $value, string $placeholder = ''): Input {
        return Input::text($name, $label)->value($value)->placeholder($placeholder);
    }

    public static function number(string $name, string $label, mixed $value): Input {
        return Input::number($name, $label)->value($value);
    }

    public static function radio(string $name, string $label, array $items, mixed $value): Input {
        return Input::radio($name, $label, $items)->value($value);
    }

    public static function size(string $name, string $label, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'name', 'label', 'value'));
    }
    public static function icon(string $name, string $label, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'name', 'label', 'value'));
    }

    public static function textarea(string $name, string $label, mixed $value, string $placeholder = ''): Input {
        return Input::textarea($name, $label)->value($value);
    }

    public static function switch(string $name, string $label, mixed $value): Input {
        return Input::switch($name, $label)->value($value);
    }

    public static function color(string $name, string $label, mixed $value): Input {
        return Input::color($name, $label)->value($value);
    }

    public static function select(string $name, string $label, array $items, mixed $value): Input {
        return Input::select($name, $label, $items)->value($value);
    }

    /**
     * 生成候选项
     * @param string $name
     * @param string|int|null $value
     * @return array
     */
    public static function selectOption(string $name, null|string|int $value = null): array {
        if (is_null($value)) {
            $value = $name;
        }
        return compact('name', 'value');
    }

    public static function group(string|array $label, array $items = []): Input {
        if (is_array($label)) {
            list($label, $items) = ['', $label];
        }
        return Input::group($label, $items);
    }

    public static function align(string $name, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'name', 'value'));
    }

    public static function textAlign(string $name, string $label, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'name', 'label', 'value'));
    }

    public static function bound(string $name, string $label, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'label', 'name', 'value'));
    }

    public static function position(string $name, string $label, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'label', 'name', 'value'));
    }

    public static function border(string $name, string $label, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'label', 'name', 'value'));
    }

    public static function background(string $name, string $label, mixed $value): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'label', 'name', 'value'));
    }

    public static function range(string $name, string $label, mixed $value, int $min = 0, int $max = 10, int $step = 1): Input {
        $type = __FUNCTION__;
        return new Input(compact('type', 'label', 'name', 'value', 'min', 'max', 'step'));
    }
}