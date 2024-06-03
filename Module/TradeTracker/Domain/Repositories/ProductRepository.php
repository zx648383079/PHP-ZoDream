<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Module\TradeTracker\Domain\Entities\ChannelProductEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Module\TradeTracker\Domain\Entities\TradeEntity;
use Module\TradeTracker\Domain\Importers\IDMapperImporter;
use Module\TradeTracker\Domain\Models\TradeModel;
use Zodream\Html\Page;

final class ProductRepository {

    public static function getGoodsList(string $keywords = '', int $category = 0, int $project = 0): Page {
        return ProductEntity::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'en_name']);
        })
        ->when($category > 0, function ($query) use ($category) {
            return $query->where('cat_id', $category);
        })
        ->when($project > 0, function ($query) use ($project) {
            return $query->where('project_id', $project);
        })->where('is_sku', 0)->page();
    }

    public static function getProductList(string $keywords = '', int $category = 0, int $project = 0): Page {
        return ProductEntity::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'en_name']);
        })
        ->when($category > 0, function ($query) use ($category) {
            return $query->where('cat_id', $category);
        })
        ->when($project > 0, function ($query) use ($project) {
            return $query->where('project_id', $project);
        })->where('is_sku', 1)->page();
    }

    public static function get(int $id): array {
        $model = ProductEntity::findOrThrow($id);
        if ($model->parent_id > 0) {
            $model = ProductEntity::findOrThrow($model->parent_id);
        }
        $data = $model->toArray();
        $items = $model->is_sku ? [] : ProductEntity::where('parent_id', $model->id)
        ->get();
        foreach ($items as $item) {
            list($_, $attr) = IDMapperImporter::splitName($item['name']);
            $data['items'][] = [
                'id' => $item['id'],
                'name' => $attr
            ];
        }
        $channelId = ChannelProductEntity::where('product_id', $id)->pluck('channel_id');
        $data['channel_items'] = empty($channelId) ? [] : ChannelEntity::whereIn('id', $channelId)->orderBy('id', 'asc')->get();
        return $data;
    }

    public static function getPrice(int $id): array {
        $dayDate = date('Y-m-d');
        $dayBegin = strtotime($dayDate. ' 00:00:00');
        $dayEnd = $dayBegin + 86400;
        $items = TradeModel::with('channel')->where('product_id', $id)->where('created_at', '>=', $dayBegin)->where('created_at', '<', $dayEnd)
        ->get();
        return $items;
    }

    public static function getPriceList(int $id, int $channel, int $type = 0, 
    string $startAt = '', string $endAt = ''): array {
        if (empty($startAt)) {
            $startAt = (empty($endAt) ? time() : strtotime($endAt)) - 31 * 86400;
        }
        $items = TradeEntity::where('product_id', $id)
        ->where('channel_id', $channel)
        ->where('type', $type)
        ->when(!empty($startAt), function($query) use ($startAt) {
            $query->where('created_at', '>=', is_numeric($startAt) ? intval($startAt) : strtotime($startAt));
        })
        ->when(!empty($endAt), function($query) use ($endAt) {
            if (!is_numeric($endAt) && !str_contains($endAt, ' ')) {
                $endAt .= ' 23:59:59';
            }
            $query->where('created_at', '<=', is_numeric($endAt) ? intval($endAt) : strtotime($endAt));
        })
        ->orderBy('created_at', 'asc')
        ->get();
        return array_map(function(TradeEntity $item) {
            $data = $item->toArray();
            $data['date'] = date('Y-m-d', $item->getAttributeSource('created_at'));
            return $data;
        }, $items);
    }

    public static function suggestion(string $keywords): array {
        return ProductEntity::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'en_name']);
        })->where('parent_id', 0)->pluck('name');
    }
}