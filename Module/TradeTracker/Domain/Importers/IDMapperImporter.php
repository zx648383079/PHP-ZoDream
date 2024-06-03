<?php
declare(strict_types=1);
namespace Module\TradeTracker\Domain\Importers;

use Domain\Providers\MemoryCacheProvider;
use Module\TradeTracker\Domain\Entities\ChannelEntity;
use Module\TradeTracker\Domain\Entities\ChannelProductEntity;
use Module\TradeTracker\Domain\Entities\ProductEntity;
use Zodream\Debugger\Domain\Console;
use Zodream\Disk\Directory;
use Zodream\Disk\File;
use Zodream\Disk\ZipStream;
use Zodream\Helpers\Json;

/**
 * 
 * @see https://github.com/EricZhu-42/SteamTradingSite-ID-Mapper
 */
class IDMapperImporter {

    const STEAM_NAME = 'steam';

    public function __construct(
        private File|Directory $file
    ) {
    }

    private function cache(): MemoryCacheProvider {
        return MemoryCacheProvider::getInstance();
    }

    public function read(): void {
        if ($this->file instanceof Directory) {
            $this->readFromFolder($this->file);
            return;
        }
        $this->readFromZip($this->file);
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

    private function getProductId(int $appId, string $hashName, string $name = '', $enName = ''): int {
        $funcName = __FUNCTION__;
        return $this->cache()->getOrSet($funcName, $hashName, function() use ($appId, $hashName, $name, $enName, $funcName) {
            list($goodsHash) = $this->splitName($hashName);
            $product = ProductEntity::where('unique_code', $hashName)
            ->first();
            if (!empty($product)) {
                if ($goodsHash !== $hashName && $product['parent_id'] > 0) {
                    $this->cache()->set($funcName, $goodsHash, $product['parent_id']);
                }
                return intval($product['id']);
            }
            $parentId = 0;
            if ($goodsHash !== $hashName) {
                list($goodsName) = $this->splitName($name);
                list($goodsEnName) = $this->splitName($enName);
                $parentId = $this->cache()->getOrSet($funcName, $goodsHash, function() use ($goodsHash, $appId, $goodsName, $goodsEnName) {
                    $id = ProductEntity::where('unique_code', $goodsHash)->value('id');
                    if ($id > 0) {
                        return intval($id);
                    }
                    $model = ProductEntity::createOrThrow([
                        'name' => $goodsName,
                        'en_name' => $goodsEnName,
                        'project_id' => $appId,
                        'unique_code' => $goodsHash,
                        'is_sku' => 0
                    ]);
                    return intval($model->id);
                });
            }
            
            $model = ProductEntity::createOrThrow([
                'parent_id' => $parentId,
                'name' => self::formatName($name),
                'en_name' => self::formatName($enName),
                'project_id' => $appId,
                'unique_code' => $hashName,
                'is_sku' => 1
            ]);
            return intval($model->id);
        });
    }

    private function bindChannelProduct(int $channelId, int $productId, string|int $hashName = ''): void {
        $exist = ChannelProductEntity::where('product_id', $productId)->where('channel_id', $channelId)->count() > 0;
        if ($exist) {
            return;
        }
        ChannelProductEntity::createOrThrow([
            'product_id' => $productId,
            'channel_id' => $channelId,
            'platform_no' => $hashName,
        ]);
    }

    private function readFromZip(File $file): void {
        $zip = new ZipStream($file);
        $items = [];
        $zip->each(function(string $name) use (&$items) {
            if (!str_ends_with($name, '.json')) {
                return;
            }
            $args = explode('/', $name);
            $appName = $args[count($args) - 2];
            $appId = intval(substr($args[count($args) - 1], 0, -5));
            $items[$appName][$appId] = $name;
        });
        if (!isset($items[self::STEAM_NAME])) {
            $zip->close();
            return;
        }
        foreach ($items[self::STEAM_NAME] as $appId => $name) {
            $this->readSteam($zip->readFile($name), $appId);
        }
        foreach ($items as $appName => $item) {
            if ($appName === self::STEAM_NAME) {
                continue; 
            }
            foreach ($item as $appId => $name) {
                $this->readOther($zip->readFile($name), $appName, $appId);
            }
        }
        $zip->close();
        $this->cache()->clear();
    }

    private function readFromFolder(Directory $folder): void {
        $items = [];
        $folder->mapDeep(function ($file) use (&$items) {
            if ($file instanceof File && $file->getExtension() === 'json') {
                $appName = $file->getDirectory()->getName();
                $appId = intval($file->getNameWithoutExtension());
                $items[$appName][$appId] = $file;
            }
        });
        if (!isset($items[self::STEAM_NAME])) {
            return;
        }
        foreach ($items[self::STEAM_NAME] as $appId => $file) {
            $this->readSteam($file->read(), $appId);
        }
        foreach ($items as $appName => $item) {
            if ($appName === self::STEAM_NAME) {
                continue; 
            }
            foreach ($item as $appId => $file) {
                $this->readOther($file->read(), $appName, $appId);
            }
        }
        $this->cache()->clear();
    }

    /**
     * @param int $appId 730 cs; 570 dota
     * @return array{en_name: string, cn_name: string, name_id: string}[]
     */
    private function readSteam(string $content, int $appId = 730): void {
        $data = Json::decode($content);
        $channelId = $this->getChannelId(self::STEAM_NAME);
        $exist = ProductEntity::where('project_id', $appId)->pluck('id', 'unique_code');
        $add = [];
        $now = time();
        foreach ($data as $hashName => $item) {
            list($goodsHash) = $this->splitName($hashName);
            if ($goodsHash !== $hashName && !isset($exist[$goodsHash])) {
                list($goodsName) = $this->splitName($item['cn_name']);
                list($goodsEnName) = $this->splitName($item['en_name']);
                $exist[$goodsHash] = ProductEntity::createOrThrow([
                    'name' => $goodsName,
                    'en_name' => $goodsEnName,
                    'project_id' => $appId,
                    'unique_code' => $goodsHash,
                    'is_sku' => 0,
                    'updated_at' => $now,
                    'created_at' => $now,
                ])->id;
            }
            if (isset($exist[$hashName])) {
                continue;
            }
            $add[] = [
                'parent_id' => $goodsHash !== $hashName ? $exist[$goodsHash] : 0,
                'name' => self::formatName($item['cn_name']),
                'en_name' => self::formatName($item['en_name']),
                'project_id' => $appId,
                'unique_code' => $hashName,
                'is_sku' => 1,
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }
        ProductEntity::query()->insert($add);
        self::printLine('update product success');
        $productIdItems = ProductEntity::query()->where('project_id', $appId)->pluck('id', 'unique_code');
        $exist = ChannelProductEntity::query()->where('channel_id', $channelId)->pluck('product_id');
        $add = [];
        foreach ($data as $hashName => $item) {
            $productId = $productIdItems[$hashName];
            if (in_array($productId, $exist)) {
                continue;
            }
            $add[] = [
                'product_id' => $productId,
                'channel_id' => $channelId,
                'platform_no' => $item['name_id'],
                'extra_meta' => $hashName,
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }
        ChannelProductEntity::query()->insert(($add));
        self::printLine('bind steam\'s product success');
        // $i = 0;
        // foreach ($data as $hash_name => $item) {
        //     $productId = $this->getProductId($appId, $hash_name, $item['cn_name'], $item['en_name']);
        //     $this->bindChannelProduct($channelId, $productId, $item['name_id'], $hash_name);
        //     self::printLine(sprintf('%s: %d/%d', self::STEAM_NAME, $i, count($data)), $i > 0);
        //     $i ++;
        // }
    }

    /**
     * 
     * @return array{en_name: string, name_id: string}[]
     */
    private function readOther(string $content, string $platformName, int $appId = 730): void {
        $data = Json::decode($content);
        $channelId = $this->getChannelId($platformName);
        $productIdItems = ProductEntity::query()->where('project_id', $appId)->pluck('id', 'unique_code');
        $exist = ChannelProductEntity::query()->where('channel_id', $channelId)->pluck('product_id');
        $add = [];
        $now = time();
        foreach ($data as $hashName => $nameId) {
            if (!isset($productIdItems[$hashName])) {
                continue;
            }
            $productId = $productIdItems[$hashName];
            if (in_array($productId, $exist)) {
                continue;
            }
            $add[] = [
                'product_id' => $productId,
                'channel_id' => $channelId,
                'platform_no' => $nameId,
                'updated_at' => $now,
                'created_at' => $now,
            ];
        }
        ChannelProductEntity::query()->insert(($add));
        self::printLine(sprintf('bind %s\'s product success', $platformName));
        // $i = 0;
        // foreach ($data as $hash_name => $nameId) {
        //     $productId = $this->getProductId($appId, $hash_name);
        //     $this->bindChannelProduct($channelId, $productId, $nameId);
        //     self::printLine(sprintf('%s: %d/%d', $platformName,  $i, count($data)), $i > 0);
        //     $i ++;
        // }
    }

    public static function printLine(string $message, bool $removeLine = false): void {
        if ($removeLine) {
            // Console::removeLine();
        }
        Console::notice($message);
    }

    public static function formatName(string $name): string {
        return str_replace(['（', '）'], ['(', ')'], $name);
    }

    public static function splitName(string $name): array {
        $name = self::formatName($name);
        if (!str_ends_with($name, ')')) {
            return [$name, ''];
        }
        $i = strrpos($name, '(');
        if ($i === false || $i === 0) {
            return [$name, ''];
        }
        return [trim(substr($name, 0, $i)), substr($name, $i + 1, strlen($name) - $i  - 2)];
    }
}