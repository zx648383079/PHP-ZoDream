<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Exception;
use Module\Shop\Domain\Models\GoodsIssueModel;

final class IssueRepository {

    const STATUS_NONE = 0;
    const STATUS_TOP = 5;
    const STATUS_DELETE = 9;

    public static function getList(int $itemId, string $keywords = '') {
        return GoodsIssueModel::query()->where('goods_id', $itemId)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['question', 'answer']);
            })
            ->where('status', '<', self::STATUS_DELETE)
            ->orderBy('status', 'desc')->page();
    }

    public static function create(int $item_id, string $content) {
        if (empty($content)) {
            throw new \Exception('question is empty');
        }
        $log = GoodsIssueModel::where('goods_id', $item_id)
            ->where('question', $content)
            ->first();
        if (!empty($log)) {
            throw new \Exception(sprintf('question is exist, %s',
                $log->answer ? 'answer: '.$log->answer : 'waiting for answer') );
        }
        GoodsIssueModel::createOrThrow([
            'ask_id' => auth()->id(),
            'goods_id' => $item_id,
            'question' => $content
        ]);
    }

    public static function manageList(int $goods = 0, string $keywords = '') {
        return GoodsIssueModel::with('goods')
            ->when($goods > 0, function ($query) use ($goods) {
                $query->where('goods_id', $goods);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['question', 'answer']);
            })
            ->orderBy('status', 'asc')->orderBy('id', 'desc')->page();
    }

    public static function manageSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $userId = auth()->id();
        $model = $id > 0 ? GoodsIssueModel::findOrThrow($id) : new GoodsIssueModel();
        $model->load($data);
        if (!$model->ask_id) {
            $model->ask_id = $userId;
        }
        if (!$model->answer_id) {
            $model->answer_id = $userId;
        }
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function manageRemove(array|int $id) {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        GoodsIssueModel::whereIn('id', $items)->delete();
    }
}