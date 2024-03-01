<?php
declare(strict_types=1);
namespace Domain\Providers;

use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Model\Model;
use Zodream\Database\Query\Builder;
use Zodream\Database\Schema\Table;
use Zodream\Helpers\Arr;
use Zodream\Helpers\Time;

/*
 * 按天统计的记录
 */
class DayLogProvider {

    protected string $logTableName;
    protected string $dayTableName;

    public function __construct(
        protected string $key
    ) {
        $this->logTableName = $this->key.'_log';
        $this->dayTableName = $this->key.'_log_day';
    }

    public function query(): Builder {
        return DB::table($this->logTableName);
    }
    public function dayQuery(): Builder {
        return DB::table($this->dayTableName);
    }


    public function migration(Migration $migration): Migration {
        return $migration->append($this->logTableName, function(Table $table) {
            $table->id();
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id');
            $table->uint('user_id')->default(0);
            $table->uint('action')->default(0);
            $table->string('ip', 120)->default('');
            $table->timestamp(Model::CREATED_AT);
        })->append($this->dayTableName, function(Table $table) {
            $table->id();
            $table->string('happen_day', 20);
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id');
            $table->uint('action')->default(0);
            $table->uint('happen_count')->default(0);
            $table->timestamp(Model::CREATED_AT);
        });
    }

    public function add(int $itemType, int $itemId, int $action) {
        $this->query()->insert([
            'item_type' => $itemType,
            'item_id' => $itemId,
            'action' => $action,
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'created_at' => time()
        ]);
    }

    public function addUnique(int $itemType, int $itemId, int $action): bool {
        $userId = auth()->id();
        $ip = request()->ip();
        $this->query()->insert([
            'item_type' => $itemType,
            'item_id' => $itemId,
            'action' => $action,
            'user_id' => $userId,
            'ip' => $ip,
            'created_at' => time()
        ]);
        return true;
    }

    protected function hasCount(int $itemType, int $itemId, int $action, string $to): bool {
        return $this->dayQuery()
                ->where('item_type', $itemType)
                ->where('item_id', $itemId)
                ->where('action', $action)->where('happen_day', $to)->count() > 0;
    }

    protected function addCount(int $itemType, int $itemId, int $action, string $to, int $start, int $end) {
        if ($this->hasCount($itemType, $itemId, $action, $to)) {
            return;
        }
        $count = $this->query()
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)->where('created_at', '>=', $start)
            ->where('<=', $end)->count();
        $this->dayQuery()->insert([
            'happen_day' => $to,
            'item_type' => $itemType,
            'item_id' => $itemId,
            'action' => $action,
            'happen_count' => $count,
            'created_at' => time()
        ]);
        $this->query()
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end)->delete();
    }

    protected function mergeCount(int $itemType, int $itemId, int $action, string $to, array $from) {
        if ($this->hasCount($itemType, $itemId, $action, $to)) {
            return;
        }
        $count = $this->dayQuery()
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)->whereIn('happen_day', $from)->count();
        $this->dayQuery()->insert([
            'happen_day' => $to,
            'item_type' => $itemType,
            'item_id' => $itemId,
            'action' => $action,
            'happen_count' => $count,
            'created_at' => time()
        ]);
        $this->dayQuery()
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)->whereIn('happen_day', $from)->delete();
    }

    public function count(int $itemType, int $itemId, int $action): int {
        return $this->query()
            ->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)->count() +
            $this->dayQuery()
                ->where('item_type', $itemType)
                ->where('item_id', $itemId)
                ->where('action', $action)->count();
    }

    public function dayCount(int $itemType, int $itemId, int $action, string $day): int {
        $log = $this->dayQuery()
            ->where('item_type', $itemType)
            ->when($itemId > 0, function ($query) use ($itemId) {
                $query->where('item_id', $itemId);
            })
            ->where('action', $action)->where('happen_day', $day)->first('happen_count');
        if (!empty($log)) {
            return intval($log['happen_count']);
        }
        $start = strtotime($day.' 00:00:00');
        return $this->query()
            ->where('item_type', $itemType)
            ->when($itemId > 0, function ($query) use ($itemId) {
                $query->where('item_id', $itemId);
            })
            ->where('action', $action)
            ->where('created_at', '>=', $start)
            ->where('created_at', '<', $start + 86400)->count();
    }

    public function todayCount(int $itemType, int $itemId, int $action): int {
        return $this->dayCount($itemType, $itemId, $action, date('Y-m-d'));
    }

    public function yesterdayCount(int $itemType, int $itemId, int $action): int {
        return $this->dayCount($itemType, $itemId, $action, date('Y-m-d', strtotime('-1 day')));
    }

    public function countByHour(int $itemType, int $itemId, int $action): array {
        $max = intval(date('H'));
        $items = [];
        $data = $this->query()->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)->where('created_at', '>=', strtotime(date('Y-m-d 00:00:00')))
            ->get('id', 'created_at');
        for ($i = 0; $i <= $max; $i ++) {
            $items[$i] = [
                'name' => $i,
                'value' => 0,
            ];
        }
        foreach ($data as $item) {
            $i = date('H', intval($item['created_at']));
            $items[$i]['value'] ++;
        }
        return array_values($items);
    }

    public function countByDay(int $itemType, int $itemId, int $action): array {
        $max = intval(date('d'));
        $items = [];
        $month = date('m');
        $year = date('Y');
        $dayItems = [];
        for ($i = 1; $i <= $max; $i ++) {
            $day = $this->twoPad($i);
            $key = sprintf('%s-%s-%s', $year, $month, $day);
            $dayItems[] = $key;
            $items[$key] = [
                'name' => sprintf('%s-%s', $month, $day),
                'value' => 0,
            ];
        }
        $data = $this->dayQuery()->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)
            ->whereIn('happen_day', $dayItems)
            ->get('happen_day', 'happen_count');
        foreach ($data as $item) {
            $items[$item['happen_day']]['value'] += intval($item['happen_count']);
        }
        return array_values($items);
    }

    public function countByMonth(int $itemType, int $itemId, int $action): array {
        $max = intval(date('m'));
        $items = [];
        $year = date('Y');
        for ($i = 1; $i <= $max; $i ++) {
            $month = $this->twoPad($i);
            $key = sprintf('%s-%s', $year, $month);
            $items[$key] = [
                'name' => $key,
                'value' => 0,
            ];
        }
        $data = $this->dayQuery()->where('item_type', $itemType)
            ->where('item_id', $itemId)
            ->where('action', $action)
            ->whereIn('happen_day', array_keys($items))
            ->get('happen_day', 'happen_count');
        foreach ($data as $item) {
            $items[$item['happen_day']]['value'] += intval($item['happen_count']);
        }
        return array_values($items);
    }

    public function countByYear(int $itemType, int $itemId, int $action): array {
        return [];
    }

    public function sortByDay(int $itemType, int $action): array {
        $start = strtotime(date('Y-m-d').' 00:00:00');
        return $this->query()
            ->where('item_type', $itemType)
            ->where('action', $action)
            ->where('created_at', '>=', $start)
             ->groupBy('item_id')
             ->selectRaw('item_id, COUNT(*) as count')
             ->orderBy('count', 'desc')->get();
    }

    public function sortByWeek(int $itemType, int $action): array {
        list($start, $end) = Time::week(\time());
        $dayItems = Time::rangeDate($start, $end);
        return $this->dayQuery()->where('item_type', $itemType)
            ->where('action', $action)
            ->whereIn('happen_day', $dayItems)
            ->selectRaw('item_id, SUM(happen_count) as count')
            ->orderBy('count', 'desc')->get();
    }

    public function sortByMonth(int $itemType, int $action): array {
        list($start, $end) = Time::month(\time());
        $dayItems = Time::rangeDate($start, $end);
        return $this->dayQuery()->where('item_type', $itemType)
            ->where('action', $action)
            ->whereIn('happen_day', $dayItems)
            ->selectRaw('item_id, SUM(happen_count) as count')
            ->orderBy('count', 'desc')->get();
    }

    public function sortByYear(int $itemType, int $action): array {
        return [];
    }

    public function mergeTask(int $itemType, int $itemId, int $action) {
        $today = strtotime(date('Y-m-d 00:00:00'));
        $yesterday = $today - 86400;
        $this->addCount($itemType, $itemId, $action, date('Y-m-d', $yesterday), $yesterday, $today - 1);
        // 合并上个月
//        $month = date('Y-m', strtotime('-1 month'));
//        $dayItems = [];
//        for ($i = 1; $i <= 31; $i ++) {
//            $dayItems[] = sprintf('%s-%s', $month, $this->twoPad($i));
//        }
//        $this->mergeCount($itemType, $itemId, $action, $month, $dayItems);
        // 合并去年
//        $year = date('Y') - 1;
//        $monthItems = [];
//        for ($i = 1; $i <= 12; $i ++) {
//            $monthItems[] = sprintf('%s-%s', $year, $this->twoPad($i));
//        }
//        $this->mergeCount($itemType, $itemId, $action, $year, $monthItems);
    }

    private function twoPad(string|int $v): string {
        if ($v < 10) {
            return '0'.$v;
        }
        return (string)$v;
    }
}