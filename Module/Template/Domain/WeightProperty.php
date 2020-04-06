<?php
namespace Module\Template\Domain;

use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\ThemeStyleModel;
use Module\Template\Module;
use Zodream\Helpers\Str;

class WeightProperty {
    public $classes = [];

    public $styles = [];

    public $data = [];


    public function formatClass() {
        return implode(' ', $this->classes);
    }

    public function formatStyle() {
        $items = [];
        foreach ($this->styles as $key => $val) {
            if (is_array($val)) {
                $val = implode(' ', $val);
            }
            $items[] = sprintf('%s: %s;', $key, $val);
        }
        return implode('', $items);
    }

    public function appendStyle(array $data) {
        $this->styles = array_merge($this->styles, $data);
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
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public static function create(PageWeightModel $model) {
        $instance = new static();
        $model->set('title', $model->title)
            ->set('content', $model->content);
        if ($model->theme_style_id > 0) {
            $style = ThemeStyleModel::find($model->theme_style_id);
            $path = (string)Module::templateFolder($style->path);
            if (file_exists($path)) {
                include_once $path;
                $name = Str::studly($style->name).'Style';
                (new $name)->render($instance);
            }
        }
        $settings = $model->settings;
        if (isset($settings['style'])) {
            $instance->appendStyle($settings['style']);
        }
        if (isset($settings['class'])) {
            $instance->pushClass($settings['class']);
        }
        foreach ($settings as $key => $val) {
            if (in_array($key, ['style', 'class'])) {
                continue;
            }
            $instance->set($key, $val);
        }
        return $instance;
    }
}
