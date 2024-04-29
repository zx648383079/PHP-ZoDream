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
            list($goodsHash) = $this->formatName($hashName);
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
                list($goodsName) = $this->formatName($name);
                list($goodsEnName) = $this->formatName($enName);
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
                'name' => $name,
                'en_name' => $enName,
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
        $i = 0;
        foreach ($data as $hash_name => $item) {
            $productId = $this->getProductId($appId, $hash_name, $item['cn_name'], $item['en_name']);
            $this->bindChannelProduct($channelId, $productId, $item['name_id'], $hash_name);
            self::printLine(sprintf('%s: %d/%d', self::STEAM_NAME, $i, count($data)), $i > 0);
            $i ++;
        }
    }

    /**
     * 
     * @return array{en_name: string, name_id: string}[]
     */
    private function readOther(string $content, string $platformName, int $appId = 730): void {
        $data = Json::decode($content);
        $channelId = $this->getChannelId($platformName);
        $i = 0;
        foreach ($data as $hash_name => $nameId) {
            $productId = $this->getProductId($appId, $hash_name);
            $this->bindChannelProduct($channelId, $productId, $nameId);
            self::printLine(sprintf('%s: %d/%d', $platformName,  $i, count($data)), $i > 0);
            $i ++;
        }
    }

    public static function printLine(string $message, bool $removeLine = false): void {
        if ($removeLine) {
            // Console::removeLine();
        }
        Console::notice($message);
    }

    public static function formatName(string $name): array {
        $name = str_replace(['（', '）'], ['(', ')'], $name);
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