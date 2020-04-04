<?php
namespace Module\SEO\Domain;

use Module\SEO\Domain\Model\OptionModel;
use Zodream\Infrastructure\Traits\SingletonPattern;

class Option {

    const CACHE_KEY = '__zo_site_option__';

    use SingletonPattern;

    private $data = [];

    private $booted = false;

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
        $items = OptionModel::query()->orderBy('position', 'desc')->get('code', 'value', 'type');
        foreach ($items as $item) {
            $data[$item->code] = $item->getFormatValueAttribute();
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
        return isset($this->data[$code]) ? $this->data[$code] : $default;
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

}
