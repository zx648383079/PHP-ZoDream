<?php
namespace Module\Shop\Domain\Repositories;


use Module\Shop\Domain\Models\Advertisement\AdModel;

class AdRepository {

    public static function banners() {
        return AdModel::getAds(1);
    }

    public static function mobileBanners() {
        return AdModel::getAds(2);
    }
}