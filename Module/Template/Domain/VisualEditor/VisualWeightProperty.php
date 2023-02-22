<?php
declare(strict_types=1);
namespace Module\Template\Domain\VisualEditor;

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\ThemeStyleModel;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;

class VisualWeightProperty {
    public array $classes = [];

    public array $styles = [];

    public array $data = [];

    public int $id = 0;

    private array $sideMap = ['top', 'right', 'bottom', 'left'];

    public function formatClass(): string {
        return implode(' ', $this->classes);
    }

    public function weightId(): string {
        return 'weight-'.$this->id;
    }

    public function formatStyle(array $data): string {
        $items = [];
        foreach ($data as $key => $val) {
            if ($key === 'title' || $key === 'content') {
                continue;
            }
            if ($key === 'visibility') {
                if ($val == 1) {
                    return 'display: none;';
                }
                continue;
            }
            $method = 'formatStyle'. Str::studly($key);
            if (method_exists($this, $method)) {
                $items[] = $this->$method($val);
                continue;
            }

            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            if (trim($val) === '') {
                continue;
            }
            $items[] = $this->formatCss($key, $val);
        }
        return implode('', $items);
    }

    public function weightStyle(): string {
        return $this->formatTagAttr('style', $this->weightCss());
    }

    public function titleStyle(): string {
        return $this->formatTagAttr('style', $this->titleCss());
    }

    public function contentStyle(): string {
        return $this->formatTagAttr('style', $this->contentCss());
    }

    private function formatTagAttr(mixed $key, string $value): string {
        return empty(trim($value)) ? '' : sprintf(' %s="%s"', $key, $value);
    }

    public function weightCss(): string {
        return $this->formatStyle($this->styles);
    }

    public function titleCss(): string {
        return isset($this->styles['title'])
            ? $this->formatStyle($this->styles['title']) : '';
    }

    public function contentCss(): string {
        return isset($this->styles['content'])
            ? $this->formatStyle($this->styles['content']) : '';
    }

    public function appendStyle(array $data) {
        $this->styles = Arr::merge2D($this->styles, $data);
        return $this;
    }

    public function pushClass($name) {
        if (!empty($name)) {
            $this->classes[] = $name;
        }
        return $this;
    }

    public function set($key, $value) {
        if (empty($value)) {
            return $this;
        }
        $this->data[$key] = $value;
        return $this;
    }

    public function get($key, $default = null) {
        return $this->data[$key] ?? $default;
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }

    public function __get($name) {
        return $this->get($name);
    }

    private function formatPixel($val): string {
        return is_numeric($val) ? $val. 'px' : $val;
    }

    private function formatCss($key, $val): string {
        return sprintf('%s: %s;', $key, $val);
    }

    private function arrEmptyCount(array $data) {
        $i = 0;
        foreach ($data as $item) {
            if (empty($item)) {
                $i ++;
            }
        }
        return $i;
    }

    private function formatStyleMargin($val, $key = 'margin') {
        if (!is_array($val)) {
            return $val === '' ? '' : $this->formatCss($key, $this->formatPixel($val));
        }
        $val = array_map(function ($item) {
            return $item === '' ? $item : $this->formatPixel($item);
        }, $val);

        if (count($val) == 4 && $this->arrEmptyCount($val) === 0) {
            return $this->formatCss($key, implode(' ', $val));
        }
        $items = [];
        foreach ($val as $i => $v) {
            if ($v === '') {
                continue;
            }
            $items[] = sprintf('%s-%s: %s;', $key, $this->sideMap[$i], $this->formatPixel($v));
        }
        return implode('', $items);
    }

    private function formatStylePadding($val) {
        return $this->formatStyleMargin($val, 'padding');
    }

    private function formatStyleTextAlign($val) {
        $maps = ['left', 'center', 'right'];
        if ($val < 1) {
            return '';
        }
        return $this->formatCss('text-align', $maps[$val]);
    }

    private function formatStylePosition($val) {
        if ($val['type'] === 'static') {
            return '';
        }
        $items = [
            $this->formatCss('position', $val['type']),
        ];
        if (!isset($val['value'])) {
            return $items[0];
        }
        foreach ($val['value'] as $i => $v) {
            if ($v === '') {
                continue;
            }
            $items[] = $this->formatCss($this->sideMap[$i], $this->formatPixel($v));
        }
        return implode('', $items);
    }

    private function formatStyleBorder($val) {
        if (empty($val['side']) || empty($val['value'][0])) {
            return;
        }
        if (is_numeric($val['value'][0])) {
            $val['value'][0] = $this->formatPixel($val['value'][0]);
        }
        $v = implode(' ', $val['value']);
        if (count($val['side']) === 4) {
            return $this->formatCss('border', $v);
        }
        $items = [];
        foreach ($val['side'] as $i) {
            $items[] = sprintf('border-%s: %s;', $this->sideMap[$i], $v);
        }
        return implode('', $items);
    }

    private function formatStyleBorderRadius($val) {
        if (count($val) === $this->arrEmptyCount($val)) {
            return '';
        }
        return $this->formatCss('border-radius', implode(' ', array_map(function ($item) {
            return $item === '' ? 0 : $this->formatPixel($item);
        }, $val)));
    }

    private function formatStyleBackground($val) {
        if ($val['type'] < 1) {
            return '';
        }
        if ($val['type'] == 1) {
            return $this->formatCss('background-color', $val['value']);
        }
        return $this->formatCss('background-image', sprintf('url(%s)', url()->asset($val['value'])));
    }

    private function formatStyleColor($val) {
        if (!isset($val['type']) || $val['type'] < 1) {
            return '';
        }
        return $this->formatCss('color', $val['value']);
    }

    public static function create(PageWeightModel $model) {
        $instance = new static();
        $model->set('title', $model->title)
            ->set('content', $model->content);
        if ($model->theme_style_id > 0) {
            $style = ThemeStyleModel::find($model->theme_style_id);
            $path = (string)VisualPage::templateFolder($style->path);
            if (file_exists($path)) {
                include_once $path;
                $name = Str::studly($style->name).'Style';
                (new $name)->render($instance);
            }
        }
        $settings = $model->settings;
        if (empty($settings)) {
            $settings = [];
        } elseif (!is_array($settings)) {
            $settings = Json::decode($settings);
        }
        if (isset($settings['style'])) {
            $instance->appendStyle($settings['style']);
        }
        if (isset($settings['class'])) {
            $instance->pushClass($settings['class']);
        }
        $instance->id = $model->id;
        foreach ($settings as $key => $val) {
            if (in_array($key, ['style', 'class'])) {
                continue;
            }
            $instance->set($key, $val);
        }
        return $instance;
    }
}
