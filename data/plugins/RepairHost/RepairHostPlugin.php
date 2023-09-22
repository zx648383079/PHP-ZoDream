<?php
declare(strict_types=1);
namespace ZoDream\RepairHost;

use Module\Plugin\Domain\IPlugin;
use Zodream\Database\DB;

final class RepairHostPlugin implements IPlugin {

    public function initiate(): void {

    }

    public function destroy(): void {

    }

    public function __invoke(array $configs = []): void {
        $original = $this->format($configs['original']);
        $host = $this->format($configs['host']);
        if (empty($original)) {
            return;
        }
        $keys = ['thumb', 'content'];
    }

    /**
     * 更新一个表
     * @param string $original
     * @param string $host
     * @param string $table
     * @param array $keys
     * @return void
     */
    private function repairTable(string $original, string $host, string $table, array $keys): void {
        $size = 30;
        $offset = 0;
        while (true) {
            $items = DB::table($table)->orderBy('id', 'asc')
                ->limit($offset, $size)->get('id', ...$keys);
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