<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\SiteWeightModel;
use Zodream\Helpers\Str;
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
        $style = $model->setting('style', []);
        if (!is_array($style)) {
            $style = [];
        }
        $title = $style['title'] ?? [];
        $content = $style['content'] ?? [];
        return [
            self::group('整体', [
                self::bound('style[margin]', '外边距', $style['margin'] ?? null),
                self::position('style[position]', '布局', $style['position'] ?? null),
                self::border('style[border]', '边框', $style['border'] ?? null),
                self::color('style[color]', '前景色', $style['color'] ?? null),
                self::background('style[background]', '背景色', $style['background'] ?? null),
            ]),
            self::group('标题', [
                self::switch('style[title][visibility]', '可见',
                    array_key_exists('visibility', $title) ?
                    intval($title['visibility']) : 1),
                self::bound('style[title][padding]', '内边距', $title['padding'] ?? null),
                self::border('style[title][border]', '边框', $title['border'] ?? null),
                self::background('style[title][background]', '背景色', $title['background'] ?? null),
                self::textAlign('style[title][text-align]', '对齐', $title['text-align'] ?? null),
                self::color('style[title][color]', '前景色', $title['color'] ?? null),
                self::size('style[title][font-size]', '字体大小', $title['font-size']?? 16),
                self::range('style[title][font-weight]', '字体粗细',
                    $title['font-weight'] ?? 500,
                    100, 900, 100),
                self::select('style[title][font-style]', '字体样式', [
                    self::selectOption('normal'),
                    self::selectOption('italic'),
                    self::selectOption('oblique'),
                ], $title['font-style'] ?? null),
                self::select('style[title][font-family]', '字体', [
                    self::selectOption('默认'),
                    self::selectOption('微软雅黑'),
                    self::selectOption('黑体'),
                    self::selectOption('宋体'),
                ], $title['font-family'] ?? null),
            ]),
            self::group('内容', [
                self::switch('style[content][visibility]', '可见', array_key_exists('visibility', $content) ?
                    intval($content['visibility']) : 1),
                self::bound('style[content][padding]', '内边距', $content['padding'] ?? null),
                self::border('style[content][border]', '边框', $content['border'] ?? null),
                self::background('style[content][background]', '背景色', $content['background'] ?? null),
                self::textAlign('style[content][text-align]', '对齐', $content['text-align'] ?? null),
                self::color('style[content][color]', '前景色', $content['color'] ?? null),
                self::size('style[content][font-size]', '字体大小', $content['font-size'] ?? 16),
                self::range('style[content][font-weight]', '字体粗细', $content['font-weight'] ?? 500,
                    100, 900, 100),
                self::select('style[content][font-style]', '字体样式', [
                    self::selectOption('normal'),
                    self::selectOption('italic'),
                    self::selectOption('oblique'),
                ], $content['font-style'] ?? null),
                self::select('style[content][font-family]', '字体', [
                    self::selectOption('默认'),
                    self::selectOption('微软雅黑'),
                    self::selectOption('黑体'),
                    self::selectOption('宋体'),
                ], $content['font-family'] ?? null),
                self::typography('font', 'Typography', null)
            ]),
        ];
    }

    public static function themeSetting(): array {
        return [
            self::group('常用', [
                self::color('theme[background_color]', 'Site Background', ''),
                self::bound('theme[content_margin]', 'Content Margin', '')->tip('Space between header and footer.'),
                self::bound('theme[element_margin]', 'Element Margin', ''),
                self::bound('theme[root_margin]', 'Root Container Margin', ''),
                self::size('container_width', 'Container Width', ''),
            ]),
            self::group('颜色', [
                self::color('theme[primary]', 'Primary Color', ''),
                self::color('theme[secondary]', 'Secondary Color', ''),
                self::color('theme[light]', 'Light Color', ''),
                self::color('theme[dark]', 'Dark Color', ''),
                self::color('theme[muted]', 'Muted Color', ''),
                self::color('theme[border]', 'Border Color', ''),
                self::color('theme[info]', 'Info Color', ''),
                self::color('theme[success]', 'Success Color', ''),
                self::color('theme[warning]', 'Warning Color', ''),
                self::color('theme[danger]', 'Danger Color', ''),
            ]),
            self::group('自定义代码', [
                self::codeEditor('theme[style]', '样式', '', 'css'),
                self::codeEditor('theme[script]', '脚本', '', 'javascript')
            ]),
        ];
    }

    public static function pageSetting(): array {
        return [
            self::group('常用', [
                self::switch('page[disable_header]', 'Disable Header', 0),
                self::switch('page[disable_footer]', 'Disable Footer', 0),
                self::color('page[background_color]', 'Site Background', ''),
                self::bound('page[content_margin]', 'Content Margin', '')->tip('Space between header and footer.'),
                self::bound('page[element_margin]', 'Element Margin', ''),
                self::bound('page[root_margin]', 'Root Container Margin', ''),
                self::size('page[container_width]', 'Container Width', ''),
            ]),
            self::group('SEO', [
                self::text('page[permalink]', 'Permalink', ''),
                self::text('page[seo_title]', 'Title', ''),
                self::text('page[seo_keywords]', 'Meta Keywords', ''),
                self::textarea('page[seo_description]', 'Meta Description', ''),
                self::select('page[seo_robot]', 'Meta Robots', [
                    self::selectOption('noindex'),
                    self::selectOption('nofollow'),
                    self::selectOption('none'),
                    self::selectOption('noarchive'),
                    self::selectOption('nocache'),
                    self::selectOption('nosnippet'),
                    self::selectOption('notranslate'),
                    self::selectOption('noimageindex'),
                ], '')->multiple(true),
            ]),
            self::group('自定义代码', [
                self::codeEditor('page[style]', '样式', '', 'css'),
                self::codeEditor('page[script]', '脚本', '', 'javascript')
            ]),
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
        return Input::textarea($name, $label)->value($value)->placeholder($placeholder);
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

    public static function typography(string $name, string $label, mixed $value): Input {
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