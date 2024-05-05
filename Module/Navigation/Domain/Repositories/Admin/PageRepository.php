<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Models\PageKeywordModel;
use Module\Navigation\Domain\Models\PageModel;

final class PageRepository {
    public static function getList(string $keywords = '', int $user = 0, int $site = 0, string $domain = '') {
        return PageModel::with('site', 'user', 'keywords')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['title']);
        })->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when($site > 0, function ($query) use ($site) {
            $query->where('site_id', $site);
        })->when(!empty($domain), function ($query) use ($domain) {
            $query->whereLike('link', '%'. $domain .'%');
        })->orderBy('id', 'desc')->page();
    }

    public static function get(int $id) {
        return PageModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data, bool $checkSite = true) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = PageModel::findOrNew($id);
        $model->load($data);
        if (static::exist($model)) {
            throw new \Exception('网页已存在');
        }
        if ($checkSite) {
            $site = SiteRepository::findIdByLink($model->link);
            if (!empty($site)) {
                $model->site_id = $site->id;
                $model->score = $site->score;
            }
        }

        if (!$model->save() && !$model->isNotChangedError()) {
            throw new \Exception($model->getFirstError()??'数据无更新');
        }
        if (!isset($data['keywords'])) {
            $data['keywords'] = [];
        } elseif (is_string($data['keywords'])) {
            $data['keywords'] = explode(',', $data['keywords']);
        }
        KeywordRepository::bindTag(
            PageKeywordModel::query(),
            $model->id,
            'page_id',
            $data['keywords'],
            [],
            'word_id'
        );
        return $model;
    }

    public static function exist(PageModel $model): bool {
        return PageModel::where('link', $model->link)
                ->where('id', '<>', $model->id)->count() > 0;
    }

    public static function check(string $link, int $id = 0): ?PageModel {
        return PageModel::where('link', $link)->where('id', '<>', $id)->first();
    }

    public static function remove(int $id) {
        PageModel::where('id', $id)->delete();
        PageKeywordModel::where('page_id', $id)->delete();
    }

    public static function crawlSave(array $data) {
        return self::save($data);
    }
}
