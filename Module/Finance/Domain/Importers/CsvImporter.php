<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

abstract class CsvImporter implements IImporter {

    protected function firstRowContains($resource, string $tag): bool {
        fseek($resource, 0);
        $line = fgets($resource);
        return str_contains($this->formatLine($line), $tag);
    }

    public function read($resource): array {
        $items = [];
        $this->readCallback($resource, function (array $item) use (&$items) {
            $items[] = $item;
        });
        return $items;
    }

    public function readCallback($resource, callable $cb): bool {
        fseek($resource, 0);
        $this->ready();
        $status = 0;
        $column = [];
        while (($data = fgetcsv($resource)) !== false) {
            if ($status === 0) {
                if (str_starts_with($data[0], '---')) {
                    $status = 1;
                }
                continue;
            }
            if (str_starts_with($data[0], '---')) {
                break;
            }
            $items = [];
            foreach ($data as $item) {
                // 修复utf8下分割不准确问题
                $items = array_merge($items, explode(',', $item));
            }
            $data = array_map(function ($item) {
                return $this->formatLine($item);
            }, $items);
            unset($items);
            if ($status === 1) {
                $column = $data;
                $status = 2;
                continue;
            }
            $item = array_combine($column, $data);
            call_user_func($cb, $this->formatData($item));
        }
        return true;
    }

    protected function ready() {

    }

    abstract protected function formatData(array $item): array;

    protected function formatLine(string $line): string {
        return trim($line);
    }
}