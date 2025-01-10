<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Module\TradeTracker\Domain\Entities\ChannelProductEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Module\TradeTracker\Domain\Entities\TradeEntity;
use Module\TradeTracker\Domain\Entities\TradeLogEntity;
use Module\TradeTracker\Domain\Models\TradeModel;
use Zodream\Html\Page;

final class TrackRepository {


    public static function latestList(string $keywords = '', int $channel = 0, int $project = 0, int $product = 0): Page {
        $dayDate = date('Y-m-d');
        $dayBegin = strtotime($dayDate. ' 00:00:00');
        $dayEnd = $dayBegin + 86400;
        // select a.* from tb a inner join (select name , min(val) val from tb group by name) b on a.name = b.name and a.val = b.val order by a.name
        $productId = null;
        if ($product > 0) {
            $productId = ProductEntity::where(function ($query) use ($productId) {
                $query->where('id', $productId)->orWhere('parent_id', $productId);
            })->where('is_sku', 1)->pluck('id');
        } elseif (!empty($keywords) || $project > 0) {
            $productId = ProductEntity::when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name', 'en_name']);
            })
                ->when($project > 0, function ($query) use ($project) {
                    return $query->where('project_id', $project);
                })->where('is_sku', 1)->pluck('id');
        }
        if (is_array($productId) && empty($productId)) {
            return new Page(0);
        }
        return TradeModel::with('product', 'channel')
            ->when($channel > 0, function ($query) use ($channel) {
                return $query->where('a.channel_id', $channel);
            })->when(is_array($productId), function ($query) use ($productId) {
                $query->whereIn('a.product_id', $productId);
            })->where('a.created_at', '>=', $dayBegin)->where('a.created_at', '<', $dayEnd)
        ->joinRaw(
            TradeModel::when($channel > 0, function ($query) use ($channel) {
                return $query->where('channel_id', $channel);
            })->when(is_array($productId), function ($query) use ($productId) {
                $query->whereIn('product_id', $productId);
            })->where('created_at', '>=', $dayBegin)->where('created_at', '<', $dayEnd)
            ->groupBy('product_id')
            ->selectRaw('product_id,MIN(price) as price'),
            'b',
            'a.product_id=b.product_id and a.price=b.price'
        )
        ->alias('a')->selectRaw('a.*')->orderBy('created_at', 'desc')->page();
    }



    /**
     * 
     * @param array{product_id: int, channel_id: int, type?: int, price: float, order_count?: int, created_at: int|string} $data
     */
    public static function addTradeLog(array $data): void {
        $data['created_at'] = is_numeric($data['created_at']) ? intval($data['created_at']) : strtotime($data['created_at']);
        if (empty($data['price']) || $data['price'] >= 9999999) {
            if (!empty($data['order_count'])) {
                self::updateTrade($data);
            }
            return;
        }
        if (!isset($data['type'])) {
            $data['type'] = 0;
        }
        
        self::addLog([
            'product_id' => $data['product_id'],
            'channel_id' => $data['channel_id'],
            'type' => $data['type'],
            'price' => $data['price'],
            'created_at' => $data['created_at'],
        ]);
        self::addTrade([
            'product_id' => $data['product_id'],
            'channel_id' => $data['channel_id'],
            'type' => $data['type'],
            'price' => $data['price'],
            'order_count' => $data['order_count'] ?? 0,
            'created_at' => $data['created_at'],
        ]);
    }

    private static function updateTrade(array $data): void {
        $dayDate = date('Y-m-d', $data['created_at']);
        $dayBeign = strtotime($dayDate. ' 00:00:00');
        $dayEnd = $dayBeign + 86400;
        TradeEntity::where([
            'product_id' => $data['product_id'],
            'channel_id' => $data['channel_id'],
            'type' => $data['type'],
        ])->where('created_at', '>=', $dayBeign)->where('created_at', '<', $dayEnd)->where('order_count', '<', $data['order_count'])->update([
            'order_count' => $data['order_count']
        ]);
    }

    private static function addTrade(array $data): void {
        $dayDate = date('Y-m-d', $data['created_at']);
        $dayBeign = strtotime($dayDate. ' 00:00:00');
        $dayEnd = $dayBeign + 86400;
        /** @var TradeEntity $log */
        $log = TradeEntity::where([
            'product_id' => $data['product_id'],
            'channel_id' => $data['channel_id'],
            'type' => $data['type'],
        ])->where('created_at', '>=', $dayBeign)->where('created_at', '<', $dayEnd)->first();
        if (empty($log)) {
            TradeEntity::createOrThrow($data);
            return;
        }
        if ($log->getAttributeSource('created_at') == $data['created_at']) {
            return;
        }
        if ($log['order_count'] < $data['order_count']) {
            $log['order_count'] = $data['order_count'];
        }
        if (($data['type'] > 0 && $log['price'] < $data['price']) || 
        ($data['type'] < 1 && $log['price'] > $data['price'])) {
            $log['price'] = $data['price'];
        }
        $log->save();
    }

    private static function addLog(array $data) : void {
        $exist = TradeLogEntity::where([
            'product_id' => $data['product_id'],
            'channel_id' => $data['channel_id'],
            'type' => $data['type'],
        ])->where('created_at', '<=', $data['created_at'])
        // 不超过1小时的可以合并
        ->where('created_at', '>', $data['created_at'] - 3600)->orderBy('created_at', 'desc')->asArray()->first();
        if ($exist && ($exist['price'] == $data['price'] || $exist['created_at'] == $data['created_at'])) {
            return;
        }
        TradeLogEntity::createOrThrow($data);
    }

    public static function batchLatestList(string $channel, array|string $product, string $to): array {
        $channelId = empty($channel) ? 0 : intval(ChannelEntity::where('short_name', $channel)->value('id'));
        $maps = [];
        if ($channelId < 1) {
            $productId = self::formatArray((array)$product, 'intval');
        } else {
            $product = self::formatArray((array)$product);
            if (empty($product)) {
                return [];
            }
            $maps = ChannelProductEntity::where('channel_id', $channelId)
                ->whereIn('platform_no', $product)->asArray()->pluck('platform_no', 'product_id');
            $productId = array_keys($maps);
        }
        if (empty($productId)) {
            return [];
        }
        $toChannelId = $to === $channel ? $channelId : intval(ChannelEntity::where('short_name', $to)->value('id'));
        if ($toChannelId < 1) {
            return [];
        }
        $dayDate = date('Y-m-d');
        $dayBegin = strtotime($dayDate. ' 00:00:00');
        $dayEnd = $dayBegin + 86400;
        $items = TradeModel::query()->whereIn('product_id', $productId)
            ->where('created_at', '>=', $dayBegin)->where('created_at', '<', $dayEnd)
            ->where('channel_id', $toChannelId)
            ->where('type', 0)->asArray()->get('product_id', 'price', 'created_at');
        $res = [];
        foreach ($items as $item) {
            $res[] = [
                'product' => empty($maps) ? intval($item['product_id']) : $maps[$item['product_id']],
                'price' => floatval($item['price']),
                'created_at' => intval($item['created_at'])
            ];
        }
        return $res;
    }

    private static function formatArray(array $items, callable|null $cb = null): array {
        $data = [];
        foreach ($items as $item) {
            if (empty($item)) {
                continue;
            }
            if (!$cb) {
                $data[] = $item;
                continue;
            }
            $val = call_user_func($cb, $item);
            if (empty($val)) {
                continue;
            }
            $data[] = $val;
        }
        return $data;
    }
}