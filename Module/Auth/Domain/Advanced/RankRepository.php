<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Advanced;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Advanced\Entities\RankEntity;
use Module\Auth\Domain\Entities\UserEntity;
use Module\Auth\Domain\Model\UserMetaModel;

final class RankRepository {
    const CACHE_KEY = 'rank_rules';

    public static function getList(string $keywords) {
        $page = RankEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->orderBy('level', 'asc')->page();
        return $page;
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? RankEntity::findOrThrow($id) : new RankEntity();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        cache()->delete(self::CACHE_KEY);
        return $model;
    }

    public static function remove(int $id) {
        RankEntity::where('id', $id)->delete();
        cache()->delete(self::CACHE_KEY);
    }

    public static function update(int $user, int $exp) {
        if ($exp === 0) {
            return;
        }
        $log = UserMetaModel::where('user_id', $user)
            ->where('name', 'rank_exp')->first();
        $userExp = 0;
        if (!empty($log)) {
            $userExp = intval($log['content']);
        }
        $oldLevel = self::getLevel($userExp);
        $userExp += $exp;
        $level = self::getLevel($userExp);
        if ($log) {
            UserMetaModel::query()->where('user_id', $user)
                ->where('name', 'rank_exp')->update([
                    'content' => $userExp
                ]);
        } else {
            UserMetaModel::query()->insert([
                'user_id' => $user,
                'name' => 'rank_exp',
                'content' =>  $userExp
            ]);
        }
        if ($oldLevel !== $level) {
            UserEntity::where('id', $user)->update([
                'rank_level' => $level
            ]);
        }
    }

    /**
     * 刷新所有等级 
     */
    public static function refresh() {
        set_time_limit(0);
        $per_page = 500;
        $offset = 0;
        while (true) {
            $items = UserMetaModel::where('name', 'rank_exp')
                ->limit($offset, $per_page)
                ->asArray()
                ->get('user_id', 'content');
            foreach ($items as $item) {
                UserEntity::where('id', $item['user_id'])->update([
                    'rank_level' => self::getLevel(intval($item['content']))
                ]);
            }
            if (count($items) < $per_page) {
                break;
            }
            $offset += $per_page;
        }
        
    }

    /**
     * 获取下一等级的经验
     */
    public static function nextLevel(int $exp): int {
        $rules = self::cacheRules();
        for ($i = count($rules) - 1; $i >= 0; $i --) {
            $item = $rules[$i];
            if ($item['min'] > $exp) {
                return intval($item['min']);
            }
        }
        return $exp + 99;
    }


    private static function getLevel(int $exp): int {
        $rules = self::cacheRules();
        foreach ($rules as $item) {
            if ($item['min'] <= $exp) {
                return max($item['level'], 0);
            }
        }
        return 0;
    }

    private static function cacheRules(): array {
        return cache()->getOrSet(self::CACHE_KEY, function() {
            $res = RankEntity::orderBy('level', 'asc')
                ->asArray()
                ->select('exp', 'level')->get();
            $max = 0;
            foreach($res as &$item) {
                $item['level'] = intval($item['level']);
                $max += intval($item['exp']);
                $item['min'] = $max;
            }
            unset($item);
            return array_reverse($res);
        });
    }
}