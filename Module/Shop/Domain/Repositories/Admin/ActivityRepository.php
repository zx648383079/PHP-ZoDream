<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Admin;

use Domain\Model\SearchModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\ActivityTimeModel;
use Module\Shop\Domain\Models\Activity\SeckillGoodsModel;
use Module\Shop\Domain\Models\GoodsSimpleModel;
use Zodream\Database\Relation;
use Zodream\Html\Page;

class ActivityRepository {
    public static function getList(int $type, string $keywords = '') {
        $query = ActivityModel::query();
        if (in_array($type, [ActivityModel::TYPE_AUCTION, ActivityModel::TYPE_PRE_SALE, ActivityModel::TYPE_BARGAIN])) {
            $query->with('goods');
        }
        /** @var Page $page */
        $page = $query->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('type', $type)->orderBy('id', 'desc')->page();
        $page->map(function ($item) {
            if ($item->type === ActivityModel::TYPE_MIX) {
                return static::formatMix($item);
            }
            if ($item->type === ActivityModel::TYPE_LOTTERY) {
                return static::formatLottery($item);
            }
            return $item;
        });
        return $page;
    }

    public static function get(int $type, int $id) {
        $model = ActivityModel::where('type', $type)->where('id', $id)
            ->first();
        if (empty($model)) {
            throw new \Exception('数据有误');
        }
        if ($type === ActivityModel::TYPE_MIX) {
            return static::formatMix($model);
        }
        if ($type === ActivityModel::TYPE_LOTTERY) {
            return static::formatLottery($model);
        }
        return $model;
    }

    protected static function formatMix(ActivityModel $model) {
        $data = $model->toArray();
        if (!isset($data['configure']['goods'])) {
            return $data;
        }
        $data['configure']['goods']  = Relation::create($data['configure']['goods'], [
            'goods' => [
                'query' => GoodsSimpleModel::query(),
                'link' => ['goods_id', 'id'],
                'type' => Relation::TYPE_ONE
            ]
        ]);
        return $data;
    }

    protected static function formatLottery(ActivityModel $model) {
        $data = $model->toArray();
        if (!isset($data['configure']['items'])) {
            return $data;
        }
        $data['configure']['items']  = Relation::create($data['configure']['items'], [
            'goods' => [
                'query' => GoodsSimpleModel::query(),
                'link' => ['goods_id', 'id'],
                'type' => Relation::TYPE_ONE
            ]
        ]);
        return $data;
    }

    public static function save(int $type, array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ActivityModel::findOrNew($id);
        if ($data['scope_type'] < 1) {
            $data['scope'] = '';
        }
        if (isset($data['scope']) && is_array($data['scope'])) {
            $data['scope'] = implode(',', array_map(function ($item) {
                return is_array($item) ? $item['id'] : $item;
            }, $data['scope']));
        }
        $model->load($data);
        $model->type = $type;
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $type, int $id) {
        ActivityModel::where('id', $id)->where('type', $type)->delete();
    }

    public static function timeList() {
        return ActivityTimeModel::orderBy('start_at asc')->get();
    }

    public static function time(int $id) {
        return ActivityTimeModel::findOrThrow($id, '数据错误');
    }

    public static function timeSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = ActivityTimeModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function timeRemove(int $id) {
        ActivityTimeModel::where('id', $id)->delete();
    }

    public static function goodsList(int $activity, int $time) {
        return SeckillGoodsModel::with('goods')->where('act_id', $activity)->where('time_id', $time)->page();
    }

    public static function goodsSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if ($id > 0) {
            $model = SeckillGoodsModel::find($id);
        } else {
            $model = SeckillGoodsModel::where('act_id', $data['act_id'])
                ->where('time_id', $data['time_id'])
                ->where('goods_id', $data['goods_id'])->first();
        }
        if (empty($model)) {
            $model = new SeckillGoodsModel();
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function goodsRemove(int $id) {
        SeckillGoodsModel::where('id', $id)->delete();
    }
}