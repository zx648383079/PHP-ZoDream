<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

use Module\Finance\Domain\Model\MoneyAccountModel;

final class WxImporter implements IImporter {

    public function is($resource): bool {
        fseek($resource, 0);
        $line = fgets($resource);
        return str_contains($line, '微信');
    }

    public function read($resource): array
    {
        $items = [];
        $this->readCallback($resource, function (array $item) use (&$items) {
           $items[] = $item;
        });
        return $items;
    }

    public function readCallback($resource, callable $cb): bool {
        fseek($resource, 0);
        $accountId = MoneyAccountModel::auth()->where('name', '微信')
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
            if ($status === 1) {
                $column = $data;
                $status = 2;
                continue;
            }
            $item = array_combine($column, $data);
            call_user_func($cb, [
                'type' => $item['收/支'] == '支出' ? 0 : 1,
                'money' => preg_replace('/[^\d\.]+/', '', $item['金额(元)']),
                'frozen_money' => 0,
                'account_id' => $accountId,
                'channel_id' => 0,
                'project_id' => 0,
                'budget_id' => 0,
                'remark' => sprintf('%s %s', $item['交易对方'], $item['商品']),
                'user_id' => auth()->id(),
                'out_trade_no' => 'wx'.$item['交易单号'],
                'created_at' => time(),
                'updated_at' => time(),
                'happened_at' => $item['交易时间'],
            ]);
        }
        return true;
    }
}