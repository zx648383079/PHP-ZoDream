<?php
namespace Module\Book\Domain;

use Module\Auth\Domain\Model\UserMetaModel;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Cookie;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Traits\Attributes;

class Setting {

    use Attributes;

    const KEY = 'book_settings';

    protected $isNew = true;

    protected $isChange = false;

    protected $__attributes = [];


    public function load() {
        if (!Auth::guest()) {
            $this->__attributes = UserMetaModel::getArr(self::KEY);
        }
        if (empty($this->__attributes)) {
            $this->init();
        } else {
            $this->isNew = false;
        }
        foreach ($this->__attributes as $key => $item) {
            $this->__attributes[$key] = Request::cookie($key, $item);
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
            if ($item == Request::cookie($key)) {
                continue;
            }
            Cookie::forever($key, $item);
        }
        return $this;
    }

    public function save() {
        $this->apply();
        if (Auth::guest()) {
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
        return parent::setAttribute($key, $value);
    }
}