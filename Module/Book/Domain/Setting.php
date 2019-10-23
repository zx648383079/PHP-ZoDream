<?php
namespace Module\Book\Domain;

use Module\Auth\Domain\Model\UserMetaModel;

use Zodream\Infrastructure\Cookie;

use Zodream\Infrastructure\Traits\Attributes;

class Setting {

    use Attributes;

    const KEY = 'book_settings';

    protected $isNew = true;

    protected $isChange = false;

    protected $__attributes = [];


    public function load() {
        if (!auth()->guest()) {
            $this->__attributes = UserMetaModel::getArr(self::KEY);
        }
        if (empty($this->__attributes)) {
            $this->init();
        } else {
            $this->isNew = false;
        }
        foreach ($this->__attributes as $key => $item) {
            $this->__attributes[$key] = app('request')->cookie($key, $item);
            if ($this->__attributes[$key] != $item) {
                $this->isChange = true;
            }
        }
        return $this;
    }

    public function init() {
        if (empty($this->__attributes)) {
            $this->__attributes = [
                'theme' => 0,
                'font' => 3,
                'size' => 18,
                'width' => 800
            ];
        }
    }

    public function apply() {
        foreach ($this->__attributes as $key => $item) {
            if ($item == app('request')->cookie($key)) {
                continue;
            }
            Cookie::forever($key, $item);
        }
        return $this;
    }

    public function save() {
        $this->apply();
        if (auth()->guest()) {
            return false;
        }
        if ($this->isNew) {
            $this->isChange = false;
            return UserMetaModel::insertArr(self::KEY, $this->__attributes);
        }
        if (!$this->isChange) {
            return false;
        }
        $this->isChange = false;
        return UserMetaModel::updateArr(self::KEY, $this->__attributes);
    }

    public function setAttribute($key, $value = null) {
        if (!is_array($key)
            && isset($this->__attributes[$key])
            && $this->__attributes[$key] == $value) {
            return $this;
        }
        $this->isChange = true;
        if (is_object($key)) {
            $key = (array)$key;
        }
        if (is_array($key)) {
            $this->__attributes = array_merge($this->__attributes, $key);
            return $this;
        }
        if (empty($key)) {
            return $this;
        }
        $this->__attributes[$key] = $value;
        return $this;
    }
}