<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Importers;

use Infrastructure\IImporter;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Helpers\Str;
use Zodream\Infrastructure\Contracts\Response\ExportObject;

final class TxtImporter implements IImporter, ExportObject {

    private mixed $handle;

    public function open(string $fileName): bool
    {
        if (!Str::endWith($fileName, ['.csv', '.txt'])) {
            return false;
        }
        $this->handle = fopen($fileName, 'r');
        return true;
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
        while (($line = fgets($this->handle)) !== false) {
            $data = explode(',', $line, 2);
            if (count($data) !== 2 || empty($data[1])
                || !parse_url($data[1], PHP_URL_HOST)) {
                continue;
            }
            call_user_func($cb, [
                'title' => $data[0],
                'source' => $data[1]
            ]);
        }
        return true;
    }

    public function getName(): string
    {
        return 'live.txt';
    }

    public function getType(): string
    {
        return 'txt';
    }

    public function send()
    {
        $items = LiveModel::query()->get();
        foreach ($items as $item) {
            echo $item['title'], ',', $item['source'], "\n";
        }
    }
}