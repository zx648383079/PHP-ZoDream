<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Contact\Domain\Model\ReportModel;

class ReportRepository {
    public static function getList(string $keywords = '', int $itemType = 0, int $itemId = 0, int $type = 0) {
        return ReportModel::with('user')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['title', 'email', 'content']);
        })->when($itemType > 0, function ($query) use ($itemType) {
            $query->where('item_type', $itemType);
        })->when($itemType > 0 && $itemId > 0, function ($query) use ($itemId) {
            $query->where('item_id', $itemId);
        })->when($type > 0, function ($query) use ($type) {
            $query->where('type', $type);
        })->orderBy([
            'status' => 'asc',
            'id' => 'desc'
        ])->page();
    }

    public static function get(int $id) {
        return ReportModel::findOrThrow($id, '数据有误');
    }

    public static function create(array $data): ReportModel {
        $model = new ReportModel();
        $model->load($data);
        $model->user_id = auth()->id();
        $model->ip = request()->ip();
        if (!static::check($model)) {
            throw new \Exception('请不要重复操作');
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function check(ReportModel $model): bool {
        return ReportModel::where('item_id', $model->item_id)
            ->where('item_type', $model->item_type)
            ->when($model->user_id > 0, function ($query) use ($model) {
                $query->where('user_id', $model->user_id);
            }, function ($query) use ($model) {
                $query->where('ip', $model->ip);
            })->where('status', 0)->count() < 1;
    }

    public static function quickCreate(int $itemType, int $itemId, string $content, int|string $type = 99): ReportModel {
        $data = [
            'item_type' => $itemType,
            'item_id' => $itemId,
            'content' => $content
        ];
        if (is_numeric($type)) {
            $data['type'] = $type;
            $data['title'] = '其他投诉/举报';
        } else {
            $data['type'] = 99;
            $data['title'] = $type;
        }
        return static::create($data);
    }

    public static function change(int $id, int $status) {
        $model = static::get($id);
        $model->status = $status;
        $model->save();
        return $model;
    }

    public static function remove(int $id) {
        ReportModel::where('id', $id)->delete();
    }
}