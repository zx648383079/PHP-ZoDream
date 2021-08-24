<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Models\SiteModel;
use Module\Navigation\Domain\Models\SiteTagModel;

final class SiteRepository {
    public static function getList(string $keywords = '', int $category = 0, int $user = 0, string $domain = '') {
        return SiteModel::with('category', 'user')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when(!empty($domain), function ($query) use ($domain) {
            $query->whereLike('domain', $domain);
        })->page();
    }

    public static function get(int $id) {
        return SiteModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = SiteModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        SiteModel::where('id', $id)->delete();
        SiteTagModel::where('site_id', $id)->delete();
    }

    public static function findIdByLink(string $link): int {
        if (empty($link)) {
            return 0;
        }
        $host = parse_url($link, PHP_URL_HOST);
        return intval(SiteModel::query()->where('domain', $host)->max('id'));
    }
}
