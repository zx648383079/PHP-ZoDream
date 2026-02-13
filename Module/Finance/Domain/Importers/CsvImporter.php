<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

use Infrastructure\IImporter;
use Zodream\Helpers\Str;

abstract class CsvImporter implements IImporter {

    private mixed $handle;

    public function open(string $fileName): bool
    {
        if (!Str::endWith($fileName, ['.csv'])) {
            return false;
        }
        $handle = fopen($fileName, 'r');
        if (!$this->is($handle, $fileName)) {
            fclose($handle);
            return false;
        }
        $this->handle = $handle;
        return true;
    }

    public function close(): void 
    {
        if ($this->handle) {
            fclose($this->handle);
            $this->handle = null;
        }
    }

    protected function firstRowContains(string $tag): bool {
        fseek($this->handle, 0);
        $line = fgets($this->handle);
        return str_contains($this->formatLine($line), $tag);
    }

    public function readToEnd(): array {
        $items = [];
        $this->readCallback(function (array $item) use (&$items) {
            $items[] = $item;
        });
        return $items;
    }

    public function readCallback(callable $cb): bool {
        fseek($this->handle, 0);
        $this->ready();
        $status = 0;
        $column = [];
        while (($data = fgetcsv($this->handle)) !== false) {
            if ($status === 0) {
                if (str_starts_with((string)$data[0], '---')) {
                    $status = 1;
                }
                continue;
            }
            if (str_starts_with((string)$data[0], '---')) {
                break;
            }
            if (count($data) === 1) {
                $status = 0;
                continue;
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
            $item = $this->formatData(array_combine($column, $data));
            if (empty($item['money']) && empty($item['frozen_money'])) {
                continue;
            }
            call_user_func($cb, $item);
        }
        return true;
    }

    protected function ready() {

    }

    abstract protected function formatData(array $item): array;
    abstract protected function is($resource, string $fileName): bool;

    protected function formatLine(string $line): string {
        return trim($line);
    }
}