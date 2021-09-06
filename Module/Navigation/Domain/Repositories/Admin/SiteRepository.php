<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Navigation\Domain\Models\SiteModel;
use Module\Navigation\Domain\Models\SiteScoringLogModel;
use Module\Navigation\Domain\Models\SiteTagModel;

final class SiteRepository {
    public static function getList(string $keywords = '', int $category = 0, int $user = 0, string $domain = '') {
        return SiteModel::with('category', 'user', 'tags')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->when($category > 0, function ($query) use ($category) {
            $query->where('cat_id', $category);
        })->when($user > 0, function ($query) use ($user) {
            $query->where('user_id', $user);
        })->when(!empty($domain), function ($query) use ($domain) {
            $query->whereLike('`domain`', $domain);
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
        if ($model->user_id < 1) {
            $model->user_id = auth()->id();
        }
        if (static::exist($model)) {
            throw new \Exception('站点已存在！');
        }
        if (!$model->save() && !$model->isNotChangedError()) {
            throw new \Exception($model->getFirstError());
        }
        TagRepository::bindTag(
            SiteTagModel::query(),
            $model->id,
            'site_id',
            isset($data['tags']) && !empty($data['tags']) ? $data['tags'] : [],
        );
        return $model;
    }

    public static function exist(SiteModel $model): bool {
        return SiteModel::where('`schema`', $model->schema)
            ->where('`domain`', $model->domain)
            ->where('id', '<>', $model->id)->count() > 0;
    }

    public static function remove(int $id) {
        SiteModel::where('id', $id)->delete();
        SiteTagModel::where('site_id', $id)->delete();
    }

    public static function findIdByLink(string $link): ?SiteModel {
        if (empty($link)) {
            return null;
        }
        $host = parse_url($link, PHP_URL_HOST);
        return SiteModel::query()->where('`domain`', $host)->orderBy('id', 'desc')->first();
    }

    public static function scoring(array $data) {
        $model = SiteModel::findOrThrow($data['id'], '数据有误');
        $score = intval($data['score']);
        SiteScoringLogModel::createOrThrow([
            'site_id' => $model->id,
            'user_id' => auth()->id(),
            'score' => $score,
            'last_score' => $model->score,
            'change_reason' => $data['change_reason'] ?? '',
        ]);
        $model->score = $score;
        $model->save();
        return $model;
    }

    public static function getScoreLog(int $site) {
        return SiteScoringLogModel::where('site_id', $site)
            ->orderBy('created_at', 'desc')
            ->page();
    }
}
