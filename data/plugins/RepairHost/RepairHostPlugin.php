<?php
declare(strict_types=1);
namespace ZoDream\RepairHost;

use Module\Plugin\Domain\IPlugin;
use Zodream\Database\DB;
use Zodream\Database\Utils;

final class RepairHostPlugin implements IPlugin {

    public function initiate(): void {

    }

    public function destroy(): void {

    }

    public function __invoke(array $configs = []): void {
        $original = $this->format($configs['original']);
        $host = $this->format($configs['host']);
        $schema = empty($configs['schema']) ? DB::engine()->config('database') : $configs['schema'];
        if (empty($original)) {
            return;
        }
        // $keys = ['thumb', 'content'];
        $tableItems = DB::information()->tableList($schema);
        foreach ($tableItems as $table) {
            $table = Utils::wrapTable($table, $schema);
            $fieldItems = DB::information()->columnList($table);
            $keys = [];
            $primaryKey = '';
            foreach ($fieldItems as $item) {
                if ($item['Key'] === 'PRI') {
                    $primaryKey = $item['Field'];
                    continue;
                }
                if ($this->isStringField($item['Type'])) {
                    $keys[] = $item['Field'];
                }
            }
            if (empty($primaryKey) || empty($keys)) {
                continue;
            }
            $this->repairTable($original, $host, $table, $keys, $primaryKey);
        }
    }

    protected function isStringField(string $type): bool {
        $type = strtolower(explode('(', $type)[0]);
        return in_array($type, ['varchar', 'char', 'text', 'tinytext', 'mediumtext', 'longtext', 'json']);
    }

    /**
     * 更新一个表
     * @param string $original
     * @param string $host
     * @param string $table
     * @param array $keys
     * @param string $primaryKey
     * @return void
     */
    private function repairTable(string $original, string $host,
                                 string $table, array $keys, string $primaryKey = 'id'): void {
        $size = 30;
        $offset = 0;
        while (true) {
            $items = DB::table($table)->orderBy($primaryKey, 'asc')
                ->limit($offset, $size)->get($primaryKey, ...$keys);
            foreach ($items as $item) {
                $data = [];
                foreach ($keys as $key) {
                    if (!str_contains($item[$key], $original)) {
                        continue;
                    }
                    $data[$key] = str_replace($original, $host, $item[$key]);
                }
                if (empty($data)) {
                    continue;
                }
                DB::table($table)->where('id', $item['id'])
                    ->update($data);
            }
            if (count($items) < $size) {
                break;
            }
            $offset += $size;
        }
    }

    private function format(string $url): string {
        if (str_contains($url, '://')) {
            return $url;
        }
        return sprintf('http://%s/', $url);
    }
}