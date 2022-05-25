<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Infrastructure\IImporter;
use Module\OnlineTV\Domain\Importers\DplImporter;
use Module\OnlineTV\Domain\Importers\M3uImporter;
use Module\OnlineTV\Domain\Importers\TxtImporter;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

final class LiveRepository extends CRUDRepository {

    protected static function query(): SqlBuilder
    {
        return LiveModel::query();
    }

    protected static function createNew(): Model
    {
        return new LiveModel();
    }

    protected static function createIfNot(array $data) {
        $model = self::query()->where('title', $data['title'])->first();
        if (empty($model)) {
            $model = self::createNew();
        }
        $model->load($data);
        $model->save();
    }

    public static function import($file, string $fileName) {
        $file = (string)$file;
        if (!is_file($file)) {
            return false;
        }
        $handle = fopen($file, 'r');
        if (!$handle) {
            return false;
        }
        set_time_limit(0);
        foreach ([
                     DplImporter::class,
                     M3uImporter::class,
                     TxtImporter::class,
                 ] as $importer) {
            /** @var IImporter $instance */
            $instance = new $importer;
            if (!$instance->is($handle, $fileName)) {
                continue;
            }
            $instance->readCallback($handle, function (array $item) {
                self::createIfNot($item);
            });
            break;
        }
        fclose($handle);
        return true;
    }

    public static function export(): ExportObject {
        return new DplImporter();
    }
}
