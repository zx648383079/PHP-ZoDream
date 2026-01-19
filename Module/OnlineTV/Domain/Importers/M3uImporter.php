<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Importers;

use Infrastructure\IImporter;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

final class M3uImporter implements IImporter, ExportObject {

    private mixed $handle;

    public function open(string $fileName): bool
    {
        if (!Str::endWith($fileName, ['.m3u'])) {
            return false;
        }
        $handle = fopen($fileName, 'r');
        fseek($handle, 0);
        $line = fgets($handle);
        if (str_starts_with(trim($line), '#EXTM3U')) {
            $this->handle = $handle;
            return true;
        }
        fclose($handle);
        return false;
    }

    public function close(): void 
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }

    public function readToEnd(): array
    {
        $items = [];
        $this->readCallback(function (array $item) use (&$items) {
            $items[] = $item;
        });
        return $items;
    }

    public function readCallback(callable $cb): bool
    {
        $data = [];
        $status = 0;
        while (($line = fgets($this->handle)) !== false) {
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