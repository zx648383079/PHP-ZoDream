<?php
declare(strict_types=1);
namespace Domain\Providers;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Model\Model;
use Zodream\Database\Query\Builder;
use Zodream\Database\Relation;
use Zodream\Database\Schema\Table;
use Zodream\Helpers\Arr;
use Zodream\Html\Page;

/**
 * 评分
 */
class ScoreProvider {

    protected string $tableName;

    public function __construct(
        protected string $key
    ) {
        $this->tableName = $key.'_score';
    }

    public function query(): Builder {
        return DB::table($this->tableName);
    }

    public function migration(Migration $migration): Migration {
        return $migration->append($this->tableName, function(Table $table) {
            $table->id();
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id');
            $table->uint('user_id');
            $table->uint('score', 1)->default(6);
            $table->uint('from_type', 1)->default(0);
            $table->uint('from_id')->default(0);
            $table->timestamp(Model::CREATED_AT);
        });
    }


    /**
     * @param int $user
     * @param int $itemType
     * @param int $itemId
     * @param int $fromType
     * @param int $fromId
     * @param string $sort
     * @param string $order
     * @return Page<array>
     * @throws \Exception
     */
    public function search(int $itemType = 0,
                           int $itemId = 0, int $user = 0,
                           int $fromType = 0,
                           int $fromId = 0,
                           string $sort = 'id', string $order = 'desc'): Page {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, [
            'id',
            'created_at',
            'score'
        ]);
        $page = $this->query()->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($itemId > 0, function ($query) use ($itemId, $itemType) {
                $query->where('item_id', $itemId)->where('item_type', $itemType);
            })
            ->when($fromId > 0, function ($query) use ($fromId, $fromType) {
                $query->where('from_id', $fromId)->where('from_type', $fromType);
            })->orderBy($sort, $order)->page();
        $data = $page->getPage();
        if (empty($data)) {
            return $page;
        }
        $data = Relation::create($data, [
            'user' => Relation::make(UserSimpleModel::query(), 'user_id', 'id')
        ]);
        $page->setPage($data);
        return $page;
    }

    public function remove(int $id) {
        $this->query()->where('id', $id)->delete();
    }

    public function insert(array $data): int {
        $id = $this->query()->insert($data);
        if (empty($id)) {
            throw new \Exception('insert log error');
        }
        return intval($id);
    }

    public function update(int $id, array $data): void {
        $this->query()->where('id', $id)->update($data);
    }

    public function get(int $id): array|null {
        $data = $this->query()->where('id', $id)->first();
        return empty($data) ? null : $this->format($data);
    }

    /**
     * 是否已经评价了
     * @param int $itemId
     * @param int $itemType
     * @param int $fromType
     * @param int $fromId
     * @return bool
     * @throws \Exception
     */
    public function has(int $itemId, int $itemType = 0, int $fromType = 0,
                        int $fromId = 0): bool {
        return $this->query()->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->where('user_id', auth()->id())
            ->where('from_type', $fromType)
            ->where('from_id', $fromId)->count() > 0;
    }

    public function save(array $data): array {
        $data['user_id'] = auth()->id();
        $data['created_at'] = time();
        $data['id'] = $this->insert($data);
        $data['user'] = UserSimpleModel::converterFrom(auth()->user());
        return $data;
    }

    public function add(int $score, int $item_id, int $item_type = 0,
                        int $from_type = 0,
                        int $from_id = 0): array {
        return $this->save(compact('score', 'item_type', 'item_id', 'from_id', 'from_type'));
    }

    /***
     * 获取平均值
     * @param int $itemId
     * @param int $itemType
     * @return float
     */
    public function avg(int $itemId, int $itemType = 0): float {
        return $this->query()->where('item_id', $itemId)
            ->where('item_type', $itemType)->avg('score');
    }

    /**
     * 获取评分的统计信息
     * @param int $itemId
     * @param int $itemType
     * @return array{total: int, good: int, middle: int, bad: int, avg: float, favorable_rate: float, tags: array}
     */
    public function count(int $itemId, int $itemType = 0): array {
        $data = $this->query()->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->groupBy('score')
            ->get('score,COUNT(*) AS `count`');
        $args = [
            'total' => 0,
            'good' => 0,
            'middle' => 0,
            'bad' => 0
        ];
        $total = 0;
        foreach ($data as $item) {
            $total += $item['count'] * $item['score'];
            $args['total'] += $item['count'];
            if ($item['score'] > 7) {
                $args['good'] += $item['count'];
                continue;
            }
            if ($item['score'] < 3) {
                $args['bad'] += $item['count'];
                continue;
            }
            $args['middle'] += $item['count'];
        }
        $args['avg'] = $args['total'] > 0 ? round($total / $args['total'], 1) : 10;
        $args['favorable_rate'] = $args['total'] > 0 ?
            ceil($args['good'] * 100 / $args['total']) : 100;
        $args['tags'] = [];
        foreach ([
            'good' => '好评', 'middle' => '一般', 'bad' => '差评'
                 ] as $key => $label) {
            if ($args[$key] <= 0) {
                continue;
            }
            $args['tags'][] = [
                'name' => $key,
                'label' => $label,
                'count' => $args[$key]
            ];
        }
        return $args;
    }

    public function removeBySelf(int $id) {
        $row = $this->query()->where('id', $id)->where('user_id', auth()->id())->delete();
        if (empty($row)) {
            throw new \Exception('无权删除此评分');
        }
    }

    public function format(array $data): array {
        if (empty($data)) {
            return [];
        }
        return Arr::toRealArr($data, [
            'id' => 'int',
            'item_id' => 'int',
            'item_type' => 'int',
            'user_id' => 'int',
            'score' => 'int',
            'from_id' => 'int',
            'from_type' => 'int',
            'created_at' => 'int',
        ]);
    }

}