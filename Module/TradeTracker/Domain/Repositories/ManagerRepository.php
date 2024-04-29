<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Zodream\Html\Page;
use Exception;
use Module\TradeTracker\Domain\Entities\ChannelProductEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Module\TradeTracker\Domain\Entities\TradeEntity;
use Module\TradeTracker\Domain\Entities\TradeLogEntity;
use Module\TradeTracker\Domain\Importers\DataImporter;
use Module\TradeTracker\Domain\Importers\IDMapperImporter;
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

    public static function logImport(File $file): void {
        (new DataImporter($file))->read();
    }
}