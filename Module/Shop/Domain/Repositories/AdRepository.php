<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;


use Domain\Model\SearchModel;
use Exception;
use Module\Shop\Domain\Models\Advertisement\AdModel;
use Module\Shop\Domain\Models\Advertisement\AdPositionModel;

class AdRepository {

    public static function banners() {
        return AdModel::getAds(1);
    }

    public static function mobileBanners() {
        return AdModel::getAds(2);
    }

    public static function getList(string $keywords = '', int|string $position = 0) {
        $now = time();
        return AdModel::query()
            ->with('position')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->when(!empty($position), function ($query) use ($position) {
                if (is_numeric($position)) {
                    $query->where('position_id', $position);
                    return;
                }
                $positionId = AdPositionModel::where('name', $position)
                    ->value('id');
                if (empty($positionId)) {
                    $query->isEmpty();
                    return;
                }
                $query->where('position_id', $positionId);
            })->where('start_at', '<=', $now)
            ->where('end_at', '>', $now)->get();
    }

    public static function get(int $id) {
        $now = time();
        $model = AdModel::query()
            ->with('position')->where('id', $id)
            ->where('start_at', '<=', $now)
            ->where('end_at', '>', $now)->first();
        if (empty($model)) {
            throw new Exception('广告不存在');
        }
        return $model;
    }
}