<?php
declare(strict_types=1);
namespace Module\Navigation\Domain\Repositories;

use Module\Navigation\Domain\Models\CollectGroupModel;
use Module\Navigation\Domain\Models\CollectModel;
use Zodream\Database\Relation;

final class CollectRepository {

    public static function all() {
        $items = CollectGroupModel::where('user_id', auth()->id())->orderBy('position', 'asc')
            ->orderBy('id', 'asc')->get();
        if (empty($items)) {
            return $items;
        }
        return Relation::create($items, [
            'items' => Relation::make(CollectModel::where('user_id', auth()->id())->orderBy('position', 'asc')
                ->orderBy('id', 'asc'), 'id', 'group_id', Relation::TYPE_MANY),
        ]);
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? CollectModel::where('id', $id)->where('user_id', auth()->id())->first()
            : new CollectModel();
        if (empty($model)) {
            throw new \Exception('收藏不存在');
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!static::check($model)) {
            throw new \Exception('网址已存在');
        }
        if ($model->isNewRecord && $model->group_id < 1) {
             $model->group_id = static::getDefaultGroup($model->user_id);
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    private static function check(CollectModel $model) {
        return static::where('link', $model->link)
            ->where('user_id', $model->user_id)->where('id', '<>', intval($model->id))
            ->count() < 1;
    }

    public static function getDefaultGroup($user) {
        $id = CollectGroupModel::where('user_id', $user)
            ->value('id');
        if ($id > 0) {
            return $id;
        }
        $model = CollectGroupModel::createOrThrow([
            'name' => '默认分组',
            'user_id' => $user
        ]);
        return $model->id;
    }

    public static function remove(int $id) {
        CollectModel::where('id', $id)->where('user_id', auth()->id())->delete();
    }

    public static function clear() {
        CollectModel::where('user_id', auth()->id())->delete();
        CollectGroupModel::where('user_id', auth()->id())->delete();
    }

    public static function reset(): array {
        return [];
    }


    public static function groupSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? CollectGroupModel::where('id', $id)->where('user_id', auth()->id())->first()
            : new CollectGroupModel();
        if (empty($model)) {
            throw new \Exception('分组不存在');
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function groupRemove(int $id) {
        CollectGroupModel::where('id', $id)->where('user_id', auth()->id())->delete();
        CollectModel::where('group_id', $id)->where('user_id', auth()->id())->delete();
    }

    public static function isCollected(string $link): bool {
        return CollectModel::where('link', $link)->where('user_id', auth()->id())->count() > 0;
    }


}
