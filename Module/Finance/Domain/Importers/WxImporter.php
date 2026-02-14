<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Importers;

use Module\Finance\Domain\Model\MoneyAccountModel;
use Zodream\Html\Excel\Importer;
use Infrastructure\IImporter;
use Zodream\Helpers\Str;

final class WxImporter extends Importer implements IImporter {

    protected mixed $accountId = '';
    private \Closure|null $writeFn = null;
    private string $handle = '';

    public function open(string $fileName): bool {
        if (!Str::endWith($fileName, ['.xlsx'])) {
            return false;
        }
        $this->handle = $fileName;
        return true;
    }

    public function close(): void 
    {
    }

    /**
     * 读取所有的数据
     * @param resource $resource
     * @return array
     */
    public function readToEnd(): array {
        $items = [];
        $this->readCallback(function (array $item) use (&$items) {
            $items[] = $item;
        });
        return $items;
    }

    /**
     * 边读取边执行
     * @param $resource
     * @param callable $cb
     * @return bool
     */
    public function readCallback(callable $cb): bool {
        $this->ready();
        $this->writeFn = $cb;
        $this->import($this->handle);
        return true;
    }

    public function headingRow(): int {
        return 17;
    }

    public function model(array $row): mixed {
        $data = $this->formatData($row);
        if (empty($data['money'])) {
            return null;
        }
        call_user_func($this->writeFn, $data);
        return null;
    }

    protected function ready() {
        if (empty($this->accountId)) {
            $this->accountId = MoneyAccountModel::auth()->where('name', '微信')
                ->value('id');
        }
    }

    protected function formatData(array $item): array {
        if ($item['收/支'] === '/') {
            return [];
        }
        // $item['当前状态'] // '支付成功' '已转账' '对方已收钱' '已存入零钱' '已转账'
        return [
            'type' => $item['收/支'] == '支出' ? 0 : 1, // '支出' '收入' '/'
            'money' => preg_replace('/[^\d\.]+/', '', $item['金额(元)']),
            'frozen_money' => 0,
            'account_id' => $this->accountId,
            'channel_id' => 0,
            'project_id' => 0,
            'budget_id' => 0,
            'trading_object' => $item['交易对方'],
            'remark' => $item['商品'],
            'user_id' => auth()->id(),
            'out_trade_no' => 'wx'.$item['交易单号'],
            'created_at' => time(),
            'updated_at' => time(),
            'happened_at' => $item['交易时间'],
        ];
    }
}