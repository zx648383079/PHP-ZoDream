<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Models\PageKeywordModel;
use Module\Navigation\Domain\Models\PageModel;

final class PageRepository {
    public static function getList(string $keywords = '', int $user = 0, int $site = 0, string $domain = '') {
        return PageModel::with('site', 'user')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['title']);
        })->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when($site > 0, function ($query) use ($site) {
            $query->where('site_id', $site);
        })->when(!empty($domain), function ($query) use ($domain) {
            $query->whereLike('link', '%'. $domain .'%');
        })->page();
    }

    public static function get(int $id) {
        return PageModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = PageModel::findOrNew($id);
        $model->load($data);
        $model->site_id = SiteRepository::findIdByLink($model->link);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        PageModel::where('id', $id)->delete();
        PageKeywordModel::where('page_id', $id)->delete();
    }
}
