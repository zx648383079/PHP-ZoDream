<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Domain\Model\SearchModel;
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
        GoodsIssueModel::create([
            'ask_id' => auth()->id(),
            'goods_id' => $item_id,
            'question' => $content
        ]);
    }
}