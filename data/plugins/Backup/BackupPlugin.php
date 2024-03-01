<?php
declare(strict_types=1);
namespace ZoDream\Backup;

use Domain\Repositories\ExplorerRepository;
use Module\Plugin\Domain\IPlugin;
use Module\SEO\Domain\Repositories\SEORepository;
use Zodream\Disk\ZipStream;

class BackupPlugin implements IPlugin {

    public function initiate(): void {
        // TODO: Implement initiate() method.
    }

    public function destroy(): void {
        // TODO: Implement destroy() method.
    }

    public function __invoke(array $configs = []): void {
        // TODO: Implement __invoke() method.
    }

    private function makeAll() {
        $root = app_path();
        $includeItems = [
            'data/storage',
            'html/assets/upload'
        ];
        $excludeItems = [
            'data/bak',
            'data/cache',
            'data/views'
        ];
        SEORepository::backUpSql(true);
        $zip = ZipStream::create(
            ExplorerRepository::bakPath(
                sprintf('file_%s.zip', date('Y-m-d'))));
        foreach ($includeItems as $folder) {
            $zip->addDirectory($folder, $root->directory($folder));
        }
        $zip->close();
    }
}