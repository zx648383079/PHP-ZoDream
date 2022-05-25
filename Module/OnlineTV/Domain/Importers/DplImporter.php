<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Importers;

use Infrastructure\IImporter;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

final class DplImporter implements IImporter, ExportObject {

    public function is($resource, string $fileName): bool
    {
        if (!Str::endWith($fileName, ['.dpl'])) {
            return false;
        }
        fseek($resource, 0);
        $line = fgets($resource);
        return trim($line) === 'DAUMPLAYLIST';
    }

    public function read($resource): array
    {
        $items = [];
        $this->readCallback($resource, function (array $item) use (&$items) {
            $items[] = $item;
        });
        return $items;
    }

    public function readCallback($resource, callable $cb): bool
    {
        $data = [];
        $status = 0;
        while (($line = fgets($resource)) !== false) {
            if (empty($line)) {
                continue;
            }
            if ($status === 0) {
                $i = strpos($line, '*file*');
                if ($i > 0) {
                    $data['source'] = substr($line, $i + 6);
                    $status ++;
                }
                continue;
            }
            if ($status === 1) {
                $i = strpos($line, '*title*');
                if ($i > 0) {
                    $data['title'] = substr($line, $i + 7);
                    call_user_func($cb, $data);
                    $data = [];
                    $status = 0;
                }

            }
        }
        return true;
    }

    public function getName(): string
    {
        return 'live.dpl';
    }

    public function getType(): string
    {
        return 'dpl';
    }

    public function send()
    {
        $items = LiveModel::query()->get();
        echo "DAUMPLAYLIST\n";
        $i = 0;
        foreach ($items as $item) {
            $i ++;
            echo $i, '*file*', $item['source'], "\n";
            echo $i, '*title*', $item['title'], "\n";
        }
    }
}