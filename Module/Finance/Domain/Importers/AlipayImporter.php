<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

use Module\Finance\Domain\Model\MoneyAccountModel;

final class AlipayImporter implements IImporter {

    public function is($resource): bool {
        fseek($resource, 0);
        $line = fgets($resource);
        return str_contains($this->format($line), '支付宝');
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
        $accountId = MoneyAccountModel::auth()->where('name', '支付宝')
            ->value('id');
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
            $data = $items;
            unset($items);
            $data = array_map(function ($item) {
                return $this->format($item);
            }, $data);
            if ($status === 1) {
                $column = $data;
                $status = 2;
                continue;
            }
            $item = array_combine($column, $data);
            call_user_func($cb, [
                'type' => $item['收/支'] == '支出' ? 0 : 1,
                'money' => $item['金额（元）'],
                'frozen_money' => 0,
                'account_id' => $accountId,
                'channel_id' => 0,
                'project_id' => 0,
                'budget_id' => 0,
                'remark' => sprintf('%s %s',$item['交易对方'], $item['商品名称']),
                'user_id' => auth()->id(),
                'out_trade_no' => 'ali'.$item['交易号'],
                'created_at' => time(),
                'updated_at' => time(),
                'happened_at' => !empty($item['付款时间'])
                    ? $item['付款时间'] : $item['交易创建时间'],
            ]);
        }
        return true;
    }

    protected function format(string $line): string {
        $res = iconv('GB2312', 'UTF-8', $line);
        return is_string($res) ? $res : '';
    }
}