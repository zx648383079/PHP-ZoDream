<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Importers;

use Domain\Providers\MemoryCacheProvider;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Module\TradeTracker\Domain\Entities\ChannelProductEntity;
use Module\TradeTracker\Domain\Repositories\TrackRepository;

class CrawlImporter {

    public function __construct(
        private int $timestamp = 0
    ) {
        if (empty($this->timestamp)) {
            $this->timestamp = time();
        }
    }

    private function cache(): MemoryCacheProvider {
        return MemoryCacheProvider::getInstance();
    }


    /**
     * 
     * @param array{from: string, name: string, items: array{product: string|array{id: string,channel: string}, channel: string, price: float, order_count: int, type?: int, created_at?: int}[]} $data
     */
    public function read(array $data): void {
        foreach ($data['items'] as $item) {
            $channelId = $this->getChannelId($item['channel']);
            if ($channelId < 1) {
                continue;
            }
            $productId = is_array($item['product']) ? $this->getProductId($this->getChannelId($item['product']['channel']), $item['product']['id']) : $this->getProductId($channelId, $item['product']);
            if ($productId < 1) {
                continue;
            }
            TrackRepository::addTradeLog([
                'product_id' => $productId,
                'channel_id' => $channelId,
                'type' => $item['type'] ?? 0,
                'price' => $item['price'],
                'order_count' => $item['order_count'] ?? 0,
                'created_at' => $item['created_at'] ?? $this->timestamp,
            ]);
        }
    }


    private function getProductId(int $channelId, string $val): int {
        if ($channelId < 1) {
            return 0;
        }
        return $this->cache()->getOrSet(__FUNCTION__, sprintf('%d-%s', $channelId, $val), function() use ($channelId, $val) {
            return intval(ChannelProductEntity::where('channel_id', $channelId)->where('platform_no', $val)->value('product_id'));
        });
    }

    private function getChannelId(string $val): int {
        return $this->cache()->getOrSet(__FUNCTION__, $val, function() use ($val) {
            $channelItems = $this->getChannelItems();
            foreach ($channelItems as $item) {
                if ($item['short_name'] === $val || strpos($val, $item['site_url']) !== false) {
                    return $item['id'];
                }
            }
            return 0;
        });
    }

    private function getChannelItems(): array {
        return $this->cache()->getOrSet(__FUNCTION__, 'all', function() {
            return ChannelEntity::query()->get('id', 'short_name', 'site_url');
        });
    }
}