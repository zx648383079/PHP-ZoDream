<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Importers;

use Infrastructure\IImporter;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

final class DplImporter implements IImporter, ExportObject {

    private mixed $handle;

    public function open(string $fileName): bool
    {
        if (!Str::endWith($fileName, ['.dpl'])) {
            return false;
        }
        $handle = fopen($fileName, 'r');
        fseek($handle, 0);
        $line = fgets($handle);
        if (trim($line) === 'DAUMPLAYLIST') {
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