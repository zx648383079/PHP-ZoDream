<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;
use Exception;
use Zodream\Html\Excel\Exporter;

class LogRepository {

    public static function getList($type = 0, $keywords = null, $account = 0, $budget = 0, $start_at = null, $end_at = null) {
        return LogModel::auth()
            ->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'remark');
            })->when($account > 0, function ($query) use ($account) {
                $query->where('account_id', $account);
            })->when($budget > 0, function ($query) use ($budget) {
                $query->where('budget_id', $budget);
            })->when(!empty($start_at), function ($query) use ($start_at) {
                $query->where('happened_at', '>=', $start_at);
            })->when(!empty($end_at), function ($query) use ($end_at) {
                $query->where('happened_at', '<=', $end_at);
            })->orderBy('happened_at', 'desc')->page();
    }

    /**
     * 获取
     * @param int $id
     * @return LogModel
     * @throws Exception
     */
    public static function get(int $id) {
        $model = LogModel::auth()->where('id', $id)->first();
        if (empty($model)) {
            throw new Exception('产品不存在');
        }
        return $model;
    }

    /**
     * 保存
     * @param array $data
     * @return LogModel
     * @throws Exception
     */
    public static function save(array $data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $model = LogModel::auth()->where('id', $data['id'])->first();
            if (empty($model)) {
                throw new Exception('不存在');
            }
        } else {
            $model = new LogModel();
        }
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        if ($model->budget_id > 0) {
            BudgetRepository::get($model->budget_id)->refreshSpent();
        }
        return $model;
    }

    /**
     * 删除产品
     * @param int $id
     * @return mixed
     */
    public static function remove(int $id) {
        return LogModel::auth()->where('id', $id)->delete();
    }

    public static function batchEdit($keywords,
                                     $account_id = 0, $project_id = 0, $channel_id = 0, $budget_id = 0) {
        if (empty($keywords)) {
            return 0;
        }
        $data = compact('account_id', 'project_id', 'channel_id', 'budget_id');
        foreach ($data as $key => $item) {
            if ($item < 1) {
                unset($data[$key]);
            }
        }
        if (empty($data)) {
            return 0;
        }
        return LogModel::query()->where('user_id', auth()->id())
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'remark');
        })->update($data);
    }

    public static function saveDay(string $day, int $account_id, int $channel_id = 0, int $budget_id = 0,
                                   array $breakfast = [], array $lunch = [], array $dinner = []) {
        $day = date('Y-m-d', strtotime($day));
        $data = [];
        foreach ([$breakfast, $lunch, $dinner] as $item) {
            if (empty($item) || !isset($item['money']) || $item['money'] <= 0) {
                continue;
            }
            $data[] = [
                'type' => LogModel::TYPE_EXPENDITURE,
                'money' => $item['money'],
                'frozen_money' => 0,
                'account_id' => intval($account_id),
                'channel_id' => intval($channel_id),
                'project_id' => 0,
                'budget_id' => intval($budget_id),
                'remark' => $item['remark'],
                'trading_object' => isset($item['trading_object']) ? $item['trading_object'] : '',
                'user_id' => auth()->id(),
                'created_at' => time(),
                'updated_at' => time(),
                'happened_at' => sprintf('%s %s', $day, $item['time']),
            ];
        }
        if (!empty($data)) {
            LogModel::query()->insert($data);
        }
    }
    public static function import($file) {
        $file = (string)$file;
        if (!is_file($file)) {
            return false;
        }
        $handle = fopen($file, 'r');
        if (!$handle) {
            return false;
        }
        $status = 0;
        $column = [];
        $type = 0;
        $account_id = 0;
        while (($data = fgetcsv($handle)) !== false) {
            if ($type === 0 && isset($data[0])) {
                $type = str_contains($data[0], '微信') ? 1 : 2;
            }
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
            if ($type === 2) {
                $data = array_map(function ($item) {
                    return trim(iconv('GB2312', 'UTF-8', $item));
                }, $data);
            }
            if ($status === 1) {
                $column = $data;
                $status = 2;
                $account_id = MoneyAccountModel::auth()->where('name', $type == 1 ? '微信' : '支付宝')
                    ->value('id');
                continue;
            }
            $item = array_combine($column, $data);

            if ($type === 1) {
                // 微信
                LogModel::createIfNot([
                    'type' => $item['收/支'] == '支出' ? 0 : 1,
                    'money' => preg_replace('/[^\d\.]+/', '', $item['金额(元)']),
                    'frozen_money' => 0,
                    'account_id' => $account_id,
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
            } elseif ($type === 2) {
                // 支付宝
                LogModel::createIfNot([
                    'type' => $item['收/支'] == '支出' ? 0 : 1,
                    'money' => $item['金额（元）'],
                    'frozen_money' => 0,
                    'account_id' => $account_id,
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
        }
        fclose($handle);
        return true;
    }

    public static function export() {
        return new Exporter('流水记录', [
            'id' => 'ID',
            'type' => '类型',
            'money' => '金额',
            'frozen_money' => '冻结金额',
            'account_id' => '账户',
            'channel_id' => '渠道',
            'project_id' => '项目',
            'budget_id' => '预算',
            'remark' => '备注',
            'out_trade_no' => '交易单号',
            'created_at' => '记录时间',
            'updated_at' => '更新时间',
            'happened_at' => '发生时间',
            'trading_object' => '交易对象',
        ], LogModel::query());
    }
}