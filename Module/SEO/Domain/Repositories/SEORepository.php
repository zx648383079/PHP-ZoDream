<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Repositories;

use Domain\Repositories\ExplorerRepository;
use Exception;
use Zodream\Disk\ZipStream;
use Zodream\Module\Gzo\Domain\GenerateModel;


class SEORepository {

    public static function storeItems() {
        return [['name' => '默认', 'value' => 'default'], ['name' => '用户', 'value' => 'auth'], ['name' => '页面', 'value' => 'pages'], ['name' => '部件', 'value' => 'nodes'], ];
    }

    public static function clearSql(): void {
        ExplorerRepository::removeBakFiles('sql_');
    }

    public static function backUpSql(bool $isZip = true): void {
        $root = ExplorerRepository::bakPath();
        $root->create();
        $fileName = sprintf('sql_%s.sql', date('Y-m-d'));
        $targetFileName = $isZip ? sprintf('sql_%s.zip', date('Y-m-d')) : $fileName;
        $targetFile = $root->file($targetFileName);
        set_time_limit(0);
        if ($targetFile->exist() && $targetFile->modifyTime() > (time() - 60)) {
            return;
        }
        $file = $isZip ? $root->file($fileName) : $targetFile;
        if (!GenerateModel::schema()
            ->export($file, [], false)) {
            throw new Exception('导出失败！');
        }
        if (!$isZip) {
            return;
        }
        ZipStream::create($targetFile)->addFile($file)
            ->close();
        $file->delete();
    }

    public static function sqlFiles(): array {
        return ExplorerRepository::bakFiles('sql_');
    }

    public static function clearCache(array $store = []): void {
        static::flushCache(empty($store) ? array_column(static::storeItems(), 'value') : $store);
    }

    public static function clearExcludeCache(array $exclude = []): void {
        $items = [];
        foreach (static::storeItems() as $item) {
            if (in_array($item['value'], $exclude)) {
                continue;
            }
            $items[] = $item['value'];
        }
        static::flushCache($items);
    }

    protected static function flushCache(array $storeItems): void {
        if (empty($storeItems)) {
            return;
        }
        foreach ($storeItems as $item) {
            if (empty($item)) {
                continue;
            }
            if ($item === 'default') {
                cache()->flush();
                continue;
            }
            cache()->store($item)->flush();
        }
    }


}