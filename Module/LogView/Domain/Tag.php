<?php
namespace Module\LogView\Domain;

use Module\LogView\Domain\Model\LogModel;

class Tag {

    const CACHE_KEY = 'log_tags_data';

    protected static $data = false;

    protected static function load() {
        if (static::$data !== false) {
            return;
        }
        $data = cache(self::CACHE_KEY);
        if (empty($data)) {
            $data = [];
        }
        static::$data = $data;
    }

    public static function get($name = null) {
        static::load();
        if (empty($name)) {
            return static::$data;
        }
        return isset(static::$data[$name]) ? static::$data[$name] : [];
    }

    protected static function save() {
        cache()->set(self::CACHE_KEY, static::$data);
    }

    public static function toggle($name, $value) {
        if (static::exist($name, $value)) {
            return static::remove($name, $value);
        }
        return self::set($name, $value);
    }

    public static function remove($name, $value) {
        if (!static::exist($name, $value)) {
            return;
        }
        foreach (static::$data[$name] as $key => $item) {
            if ($item == $value) {
                unset(static::$data[$name][$key]);
            }
        }
        static::save();
    }

    public static function clear() {
        cache()->delete(self::CACHE_KEY);
    }

    public static function set($name, $value) {
        static::load();
        if (!isset(static::$data[$name])) {
            static::$data[$name] = [];
        }
        static::$data[$name][] = $value;
        static::save();
    }

    public static function has(LogModel $model) {
        static::load();
        foreach ($model as $key => $item) {
            if (static::exist($key, $item)) {
                return true;
            }
        }
        return false;
    }

    public static function exist($name, $value) {
        static::load();
        if (!isset(static::$data[$name])) {
            return false;
        }
        return in_array($value, static::$data[$name]);
    }
}