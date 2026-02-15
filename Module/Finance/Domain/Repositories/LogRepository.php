<?php
declare(strict_types=1);
namespace Module\Finance\Domain\Repositories;

use Domain\Model\SearchModel;
use Infrastructure\IImporter;
use Module\Finance\Domain\Importers\AlipayImporter;
use Module\Finance\Domain\Importers\WxImporter;
use Module\Finance\Domain\Model\LogModel;
use Exception;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Html\Excel\Exporter;
use Zodream\Disk\ZipStream;
use Zodream\Helpers\Time;

class LogRepository {

    public static function getList(int $type = 0, string $keywords = '', int $account = 0, int $budget = 0, string $start_at = '', string $end_at = '', string $goto = '', int $page = 1,
                                   int $per_page = 20) {
        if (!empty($goto)) {
            $count = static::bindQuery(LogModel::auth(), $type, $keywords, $account, $budget, $start_at, $end_at)
            ->where('happened_at', '>=', $goto)
            ->count();
            $page = (int)ceil((float)$count / $per_page);
        }
        return static::bindQuery(LogModel::auth(), $type, $keywords, $account, $budget, $start_at, $end_at)->orderBy('happened_at', 'desc')->page($per_page, 'page', $page);
    }

    public static function count(int $type = 0, string $keywords = '', int $account = 0, int $budget = 0, string $start_at = '', string $end_at = '') {
        return static::bindQuery(LogModel::auth(), $type, $keywords, $account, $budget, $start_at, $end_at)->count();
    }

    protected static function bindQuery(SqlBuilder $builder, int $type = 0, string $keywords = '', int $account = 0, int $budget = 0, string $start_at = '', string $end_at = ''): SqlBuilder {
        return $builder->when($type > 0, function ($query) use ($type) {
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
        });
    }

    public static function search(string $keywords = '', int $type = 0, int|array $id = 0) {
        return LogModel::auth()
            ->where('parent_id', 0)
            ->when($type > 0, function ($query) use ($type) {
                $query->where('type', $type - 1);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['remark', 'trading_object']);
            })->when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    $query->whereIn('id', $id);
                    return;
                }
                $query->where('id', $id);
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
        if ($model->parent_id > 0) {
            $model->parent = LogModel::auth()->where('id', $model->parent_id)->first();
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
            BudgetRepository::get((int)$model->budget_id)->refreshSpent();
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

    public static function batchEdit(array $data) {
        if (empty($data['keywords'])) {
            return 0;
        }
        if (!empty($data['operator']) && intval($data['operator']) === 1) {
            return self::mergeLogByMonth($data['keywords']);
        }
        $keywords = $data['keywords'];
        unset($data['keywords'], $data['operator']);
        foreach ($data as $key => $item) {
            if (str_ends_with($key, '_id') && $item > 0) {
                continue;
            }
            if (!empty($item)) {
                continue;
            }
            unset($data[$key]);
        }
        if (empty($data)) {
            return 0;
        }
        return LogModel::query()->where('user_id', auth()->id())
            ->where(function ($query) use ($keywords) {
                SearchModel::search($query, 'remark', false, '', $keywords);
        })->when(!empty($data['trading_object']), function($query) {
            $query->where('trading_object', '');
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
                'account_id' => $account_id,
                'channel_id' => $channel_id,
                'project_id' => 0,
                'budget_id' => $budget_id,
                'remark' => $item['remark'],
                'trading_object' => $item['trading_object'] ?? '',
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

    public static function mergeLogByMonth(string $keywords): int {
        $end_at = date('Y-m-1 00:00:00');
        LogModel::auth()->where(function ($query) use ($keywords) {
            SearchModel::search($query, 'remark', false, '', $keywords);
        })->where('happened_at', '<', $end_at)->orderBy('happened_at', 'desc');
        // TODO
        return 0;
    }

    public static function import(mixed $file, string $password = ''): int {
        $file = (string)$file;
        if (!is_file($file)) {
            return 0;
        }
        $success = 0;
        $cb = function($file) use (&$success) {
            foreach ([
                     WxImporter::class,
                     AlipayImporter::class
                 ] as $importer) {
                /** @var IImporter $instance */
                $instance = new $importer;
                if (!$instance->open($file)) {
                    continue;
                }
                $instance->readCallback(function (array $item) use (&$success) {
                    LogModel::createIfNot($item);
                    $success ++;
                });
                $instance->close();
                break;
            }
        };
        if (str_ends_with($file, '.zip')) {
            $zip = new ZipStream($file);
            if (!empty($password) && !$zip->setPassword($password)) {
                $zip->close();
                throw new Exception('密码错误');
            }
            $targetFile = app_path('data/cache/__'.Time::millisecond());
            $zip->each(function(string $name, bool $isFolder) use ($targetFile, $zip, $cb) {
                if ($isFolder) {
                    return;
                }
                if (str_ends_with($name, '.csv') && $zip->extractFile($name, $outputFile = $targetFile.'.csv')) {
                    call_user_func($cb, $outputFile);
                } elseif (str_ends_with($name, '.xlsx') && $zip->extractFile($name, $outputFile = $targetFile.'.xlsx')) {
                    call_user_func($cb, $outputFile);
                }
            });
            $zip->close();
        } else {
            call_user_func($cb, $file);
        }
        return $success;
    }

    public static function export(bool $urlEncode = false) {
        set_time_limit(0);
        $title = '流水记录';
        if ($urlEncode) {
            $title = urlencode($title);
        }
        return new Exporter($title, [
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
        ], LogModel::auth());
    }
}