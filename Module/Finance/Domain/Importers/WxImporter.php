<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

use Module\Finance\Domain\Model\MoneyAccountModel;

final class WxImporter extends CsvImporter {

    protected mixed $accountId = '';

    public function is($resource, string $fileName): bool {
        return $this->firstRowContains($resource, '微信');
    }

    protected function ready() {
        if (empty($this->accountId)) {
            $this->accountId = MoneyAccountModel::auth()->where('name', '微信')
                ->value('id');
        }
    }

    protected function formatData(array $item): array {
        return [
            'type' => $item['收/支'] == '支出' ? 0 : 1,
            'money' => preg_replace('/[^\d\.]+/', '', $item['金额(元)']),
            'frozen_money' => 0,
            'account_id' => $this->accountId,
            'channel_id' => 0,
            'project_id' => 0,
            'budget_id' => 0,
            'remark' => sprintf('%s %s', $item['交易对方'], $item['商品']),
            'user_id' => auth()->id(),
            'out_trade_no' => 'wx'.$item['交易单号'],
            'created_at' => time(),
            'updated_at' => time(),
            'happened_at' => $item['交易时间'],
        ];
    }
}