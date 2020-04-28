<?php
namespace Module\Finance\Domain\Repositories;

use Module\Finance\Domain\Model\LogModel;
use Module\Finance\Domain\Model\MoneyAccountModel;

class LogRepository {

    public static function getList($type = 0, $keywords = null, $account = 0, $budget = 0, $start_at = null, $end_at = null) {
        return LogModel::auth()
            ->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })->when(!empty($keywords), function ($query) {
                LogModel::searchWhere($query, 'remark');
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
            LogModel::searchWhere($query, 'remark');
        })->update($data);
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
                $type = strpos($data[0], '微信') !== false ? 1 : 2;
            }
            if ($status === 0) {
                if (strpos($data[0], '---') === 0) {
                    $status = 1;
                }
                continue;
            }
            if (strpos($data[0], '---') === 0) {
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
}