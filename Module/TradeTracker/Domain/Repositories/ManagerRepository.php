<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Zodream\Html\Page;
use Exception;
use Module\TradeTracker\Domain\Entities\ChannelProductEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Module\TradeTracker\Domain\Entities\TradeEntity;
use Module\TradeTracker\Domain\Entities\TradeLogEntity;
use Module\TradeTracker\Domain\Importers\CrawlImporter;
use Module\TradeTracker\Domain\Importers\DataImporter;
use Module\TradeTracker\Domain\Importers\IDMapperImporter;
use Module\TradeTracker\Domain\Models\TradeLogModel;
use Zodream\Disk\File;

final class ManagerRepository {


    public static function productRemove(int $id) {
        $model = ProductEntity::findOrThrow($id);
        $items = [];
        if ($model->parent_id < 1) {
            $items = ProductEntity::where('parent_id', $id)->pluck('id');
        }
        $items[] = $id;
        ChannelProductEntity::whereIn('product_id', $items)->delete();
        TradeEntity::whereIn('product_id', $items)->delete();
        TradeLogEntity::whereIn('product_id', $items)->delete();
        ProductEntity::whereIn('id', $items)->delete();
    }

    public static function productSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if (ProductEntity::where('unique_code', $data['unique_code'])->where('id', '<>', $id)->count() > 0) {
            throw new Exception('已存在');
        }
        $model = ProductEntity::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        if (empty($data['items'])) {
            return $model;
        }
        foreach ($data['items'] as $item) {
            $channelId = ChannelEntity::where('short_name', $item['channel'])->value('id');
            if ($channelId < 0) {
                continue;
            }
            $exist = ChannelProductEntity::where('product_id', $model->id)->where('channel_id', $channelId)->count() > 0;
            if ($exist) {
                continue;
            }
            ChannelProductEntity::createOrThrow([
                'product_id' => $model->id,
                'channel_id' => $channelId,
                'platform_no' => $item['platform_no'],
                'extra_meta' => $item['extra_meta'] ?? '',
            ]);
        }
        return $model;
    }

    public static function productImport(File $file): void {
        (new IDMapperImporter($file))->read();
    }

    public static function channelList(string $keywords = '') : Page {
        return ChannelEntity::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'short_name']);
        })->orderBy('id', 'desc')->page();
    }

    public static function channelSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        if (ChannelEntity::where('short_name', $data['short_name'])->where('id', '<>', $id)->count() > 0) {
            throw new Exception('已存在');
        }
        $model = ChannelEntity::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function channelRemove(int $id) {
        ChannelEntity::where('id', $id)->delete();
        ChannelProductEntity::where('channel_id', $id)->delete();
    }

    public static function logList(int $product = 0, int $channel = 0, int $type = 0): Page {
        return TradeLogModel::with('product', 'channel')
        ->when($product > 0, function ($query) use ($product) {
            return $query->where('product_id', $product);
        })->when($channel > 0, function ($query) use ($channel) {
            return $query->where('channel_id', $channel);
        })->when($type > 0, function ($query) use ($type) {
            return $query->where('type', $type - 1);
        })->orderBy('created_at', 'desc')->page();
    }

    public static function logAdd(array $data) {
        (new CrawlImporter())->read([
            'items' => [$data]
        ]);
    }

    public static function logRemove(array|int $id): void {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        TradeLogEntity::whereIn('id', $items)->delete();
    }

    public static function logImport(File $file): void {
        (new DataImporter($file))->read();
    }

    public static function crawlSave(array $data) {
        set_time_limit(0);
        (new CrawlImporter())->read($data);
    }
}