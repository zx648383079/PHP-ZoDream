<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Importers;

use Domain\Providers\MemoryCacheProvider;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Module\TradeTracker\Domain\Entities\TradeEntity;
use Module\TradeTracker\Domain\Entities\TradeLogEntity;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\Stream;
use Zodream\Helpers\Json;

/**
 * 
 * @see https://github.com/EricZhu-42/SteamTradingSiteTracker-Data
 */
class DataImporter {

    public function __construct(
        private File|Directory $file
    ) {
    }

    private function cache(): MemoryCacheProvider {
        return MemoryCacheProvider::getInstance();
    }

    private function getChannelId(string $name): int {
        return $this->cache()->getOrSet(__FUNCTION__, $name, function() use ($name) {
            $id = ChannelEntity::where('short_name', $name)->value('id');
            if ($id > 0) {
                return intval($id);
            }
            $model = ChannelEntity::createOrThrow([
                'short_name' => $name,
                'name' => $name,
            ]);
            return intval($model->id);
        });
    }

    public function read(): void {
        if ($this->file instanceof Directory) {
            $this->readFromFolder($this->file);
            return;
        }
        $this->readFromJson($this->file);
    }

    private function readFromFolder(Directory $folder): void {
        $folder->mapDeep(function ($file) use (&$items) {
            if ($file instanceof File && $file->getExtension() === 'json') {
                $this->readFromJson($file);
            }
        });
    }

    private function readFromJson(File $file): void {
        $reader = new Stream($file);
        $i = 0;
        while (!$reader->isEnd()) {
            $line = $reader->readLine();
            if (empty($line)) {
                continue;
            }
            $this->updateLog(Json::decode($line));
            IDMapperImporter::printLine(sprintf('%s: %d', $file->getName(), $i), $i > 0);
            $i ++;
        }
    }

    private function updateLog(array $data): void {
        $items = $this->formatLog($data);
        $product = ProductEntity::where('unique_code', $items['hash_name'])->first();
        if (empty($product)) {
            return;
        }
        foreach ($items['items'] as $appName => $args) {
            $channelId = $this->getChannelId($appName);
            foreach ($args as $type => $item) {
                $this->addLog([
                    'product_id' => $product['id'],
                    'channel_id' => $channelId,
                    'type' => $type,
                    'price' => $item['price'],
                    'created_at' => $items['time'],
                ]);
                $this->addTrade([
                    'product_id' => $product['id'],
                    'channel_id' => $channelId,
                    'type' => $type,
                    'price' => $item['price'],
                    'order_count' => $item['order_count'] ?? 0,
                    'created_at' => $items['time'],
                ]);
            }
        }
    }

    private function addTrade(array $data): void {
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

    private function addLog(array $data) : void {
        $exist = TradeLogEntity::where([
            'product_id' => $data['product_id'],
            'channel_id' => $data['channel_id'],
            'type' => $data['type'],
            'created_at' => $data['created_at'],
        ])->count() > 0;
        if ($exist) {
            return;
        }
        TradeLogEntity::createOrThrow($data);
    }

    private function formatLog(array $data): array {
        $items = [];
        if (!empty($data['created_at'])) {
            $time = $data['created_at'];
            $items[IDMapperImporter::STEAM_NAME] = [
                ['price' => $data['optimal_sell_price']],
                ['price' => $data['optimal_buy_price']]
            ];
            foreach ($data as $key => $value) {
                $name = explode('_', $key, 2)[0];
                if (str_ends_with($key, '_optimal_price')) {
                    $items[$name][0] = ['price' => $value];
                }
                if (str_ends_with($key, '_buy_num')) {
                   // $items[$name][1]['order_count'] = $value;
                }
                if (str_ends_with($key, '_sell_num')) {
                    $items[$name][0]['order_count'] = $value;
                }
            }
        } else {
            $time = $data['update_time'];
            $items[IDMapperImporter::STEAM_NAME] = [
                ['price' => $data['steam_order']['sell_price'], 'order_count' => $data['steam_order']['sell_order_count']],
                ['price' => $data['steam_order']['buy_price'], 'order_count' => $data['steam_order']['buy_order_count']],
            ];
            foreach ($data as $key => $value) {
                $name = explode('_', $key, 2)[0];
                if (str_ends_with($key, '_buy')) {
                    $items[$name][1] = ['price' => $value['price'], 'order_count' => $value['count']];
                }
                if (str_ends_with($key, '_sell')) {
                    $items[$name][0] = ['price' => $value['price'], 'order_count' => $value['count']];
                }
            }
        }
        return ['hash_name' => $data['hash_name'], 'items' => $items, 'time' => $time];
    }
}