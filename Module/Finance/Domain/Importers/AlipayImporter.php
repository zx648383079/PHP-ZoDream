<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

use Module\Finance\Domain\Model\MoneyAccountModel;

final class AlipayImporter extends CsvImporter {

    protected mixed $accountId = '';

    public function is($resource, string $fileName): bool {
        return $this->firstRowContains($resource, '支付宝');
    }

    protected function ready() {
        if (empty($this->accountId)) {
            $this->accountId = MoneyAccountModel::auth()->where('name', '支付宝')
                ->value('id');
        }
    }

    protected function formatData(array $item): array {
        return [
            'type' => $item['收/支'] == '支出' ? 0 : 1,
            'money' => $item['金额（元）'],
            'frozen_money' => 0,
            'account_id' => $this->accountId,
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
        ];
    }

    protected function formatLine(string $line): string {
        $res = iconv('GB2312', 'UTF-8', $line);
        return is_string($res) ? trim($res) : '';
    }
}