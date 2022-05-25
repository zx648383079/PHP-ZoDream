<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Importers;

use Infrastructure\IImporter;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

final class M3uImporter implements IImporter, ExportObject {

    public function is($resource, string $fileName): bool
    {
        if (!Str::endWith($fileName, ['.m3u'])) {
            return false;
        }
        fseek($resource, 0);
        $line = fgets($resource);
        return str_starts_with(trim($line), '#EXTM3U');
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
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            if (str_starts_with($line, '#EXTINF:')) {
                $data['title'] = explode(',', $line, 2)[1];
                $status = 1;
                continue;
            }
            if ($status === 1  && !str_starts_with($line, '#EXTVLCOPT:')) {
                $data['source'] = $line;
                call_user_func($cb, $data);
            }
        }
        return true;
    }

    public function getName(): string
    {
        return 'live.m3u';
    }

    public function getType(): string
    {
        return 'm3u';
    }

    public function send()
    {
        $items = LiveModel::query()->get();
        echo "#EXTM3U\n";
        foreach ($items as $item) {
            echo '#EXTINF:-1,', $item['title'], "\n";
            echo $item['source'], "\n";
        }
    }
}