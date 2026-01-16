<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

use Module\Finance\Domain\Model\MoneyAccountModel;

final class AlipayImporter extends CsvImporter {

    protected mixed $accountId = '';

    public function is($resource, string $fileName): bool {
        fseek($resource, 0);
        for ($i = 0; $i < 5; $i++) { 
            $line = fgets($resource);
            if ($line === false) {
                return false;
            }
            if (str_contains($this->formatLine($line), '支付宝账户')) {
                return true;
            }
        }
        return false;
    }

    protected function ready() {
        if (empty($this->accountId)) {
            $this->accountId = MoneyAccountModel::auth()->where('name', '支付宝')
                ->value('id');
        }
    }

    protected function formatData(array $item): array {
        return [
            'type' => $item['收/支'] === '支出' ? 0 : 1,
            'money' => $item['金额'],
            'frozen_money' => 0,
            'account_id' => $this->accountId,
            'channel_id' => 0,
            'project_id' => 0,
            'budget_id' => 0,
            'trading_object' => $item['交易对方'],
            'remark' => $item['商品说明'],
            'user_id' => auth()->id(),
            'out_trade_no' => 'ali'.$item['交易订单号'],
            'created_at' => time(),
            'updated_at' => time(),
            'happened_at' => !empty($item['付款时间'])
                ? $item['付款时间'] : $item['交易时间'],
        ];
    }

    protected function formatLine(string $line): string {
        $res = iconv('GB2312', 'UTF-8', $line);
        return is_string($res) ? trim($res) : '';
    }
}