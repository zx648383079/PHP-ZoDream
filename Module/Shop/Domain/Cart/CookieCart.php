<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Cart;

final class CookieCart extends Cart {
    const COOKIE_KEY = 'cart_identifier';
    public static function cacheId(): string {
        $id = request()->cookie(self::COOKIE_KEY);
        if (!empty($id)) {
            return $id;
        }
        $id = md5(uniqid('', true));
        response()->cookie(self::COOKIE_KEY, $id, 0, '/');
        return $id;
    }

    public function load(): void {
        $data = cache(self::cacheId());
        if (!empty($data)) {
            $this->setItems($data);
        }
        parent::load();
    }

    public function save(): void {
        cache()->set(self::cacheId(), $this->items);
    }
}