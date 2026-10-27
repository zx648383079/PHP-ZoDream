<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Advanced;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Advanced\Entities\RankEntity;
use Module\Auth\Domain\Entities\UserEntity;
use Module\Auth\Domain\Model\UserMetaModel;

final class RankRepository {
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
        return $model;
    }

    public static function remove(int $id) {
        RankEntity::where('id', $id)->delete();
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

    private static function getLevel(int $exp): int {
        $rules = cache()->getOrSet('rank_rules', function() {
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
        foreach ($rules as $item) {
            if ($item['min'] < $exp) {
                return max($item['level'], 0);
            }
        }
        return 0;
    }
}