<?php
declare(strict_types=1);
namespace Module\SEO\Domain;

use Module\SEO\Domain\Model\OptionModel;
use Zodream\Helpers\Json;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Concerns\SingletonPattern;

class Option {

    const CACHE_KEY = '__zo_site_option__';

    use SingletonPattern;

    private array $data = [];

    private bool $booted = false;

    public function __construct() {
        $this->boot();
    }

    public function boot() {
        if ($this->booted) {
            return;
        }
        $this->booted = true;
        $this->data = cache()->getOrSet(self::CACHE_KEY, function () {
           return $this->format();
        });
    }

    private function format() {
        $data = [];
        /** @var OptionModel[]  $items */
        try {
            $items = OptionModel::query()->orderBy('position', 'desc')->get('code', 'value', 'type');
        } catch (\Exception) {
            $items = [];
        }
        foreach ($items as $item) {
            $data[$item->code] = static::formatOption((string)$item->getAttributeSource('value'), $item->type);
        }
        return $data;
    }

    public function clearCache() {
        cache()->delete(self::CACHE_KEY);
    }

    public function reset() {
        $this->booted = false;
        cache()->set(self::CACHE_KEY, $this->format());
    }

    public function get($code, $default = null) {
        return $this->data[$code] ?? $default;
    }

    public function __isset($code) {
        return isset($this->data[$code]);
    }

    public function __get($name) {
        return $this->get($name);
    }

    public static function value($code, $default = null) {
        return self::getInstance()->get($code, $default);
    }


    public static function __callStatic($name, $arguments) {
        $default = null;
        if (!empty($arguments)) {
            $default = $arguments[0];
        }
        return self::value($name, $default);
    }

    public static function formatOption(string $value, string $type) {
        if ($type === 'switch') {
            return Str::toBool($value);
        }
        if ($type === 'radio' || $type === 'select') {
            return self::formatIfInt($value);
        }
        if ($type === 'checkbox') {
            return array_map('self::formatIfInt', explode(',', $value));
        }
        if ($type === 'image' || $type === 'file') {
            return empty($value) ? $value : url()->asset($value);
        }
        if ($type === 'json') {
            return empty($value) ? [] : Json::decode($value);
        }
        return $value;
    }

    private static function formatIfInt(?string $val): mixed {
        if (is_null($val)) {
            return '';
        }
        if (is_numeric($val)) {
            return intval($val);
        }
        return trim($val);
    }

    public static function group(string|array $name, callable $cb) {
        return OptionModel::group($name, $cb);
    }

}
