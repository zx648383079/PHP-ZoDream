<?php
declare(strict_types=1);
namespace Domain\Providers;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\UserSimpleModel;
use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Query\Builder;
use Zodream\Database\Relation;
use Zodream\Database\Schema\Table;
use Zodream\Helpers\Arr;
use Zodream\Html\Page;

/**
 * 评分
 */
class CollectProvider {

    protected string $tableName;

    public function __construct(
        protected string $key
    ) {
        $this->tableName = $key.'_collect';
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
            $table->string('extra_data')->default('');
            $table->timestamp('created_at');
        });
    }


    /**
     * @param int $user
     * @param int $itemType
     * @param int $itemId
     * @param string $sort
     * @param string $order
     * @return Page<array>
     * @throws \Exception
     */
    public function search(int $itemType = 0,
                           int $itemId = 0, int $user = 0,
                           string $sort = 'id', string $order = 'desc'): Page {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, [
            'id',
            'created_at',
        ]);
        $page = $this->query()->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($itemId > 0, function ($query) use ($itemId, $itemType) {
                $query->where('item_id', $itemId)->where('item_type', $itemType);
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

    public function get(int $id): ?array {
        $data = $this->query()->where('id', $id)->first();
        return empty($data) ? null : $this->format($data);
    }

    /**
     * 是否已经收藏了
     * @param int $itemId
     * @param int $itemType
     * @return bool
     * @throws \Exception
     */
    public function has(int $itemId, int $itemType = 0): bool {
        return $this->query()->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->where('user_id', auth()->id())->count() > 0;
    }

    public function save(array $data): array {
        $data['user_id'] = auth()->id();
        $data['created_at'] = time();
        $data['id'] = $this->insert($data);
        $data['user'] = UserSimpleModel::converterFrom(auth()->user());
        return $data;
    }

    public function add(int $item_id, int $item_type = 0, string $extra_data = ''): array {
        return $this->save(compact('extra_data', 'item_type', 'item_id'));
    }


    /**
     * 获取评分的统计信息
     * @param int $itemId
     * @param int $itemType
     * @return int
     */
    public function count(int $itemId, int $itemType = 0): int {
        return $this->query()->where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->count();
    }

    public function removeBySelf(int $id) {
        $row = $this->query()->where('id', $id)->where('user_id', auth()->id())->delete();
        if (empty($row)) {
            throw new \Exception('无权删除此收藏');
        }
    }

    public function removeByItem(int $itemId, int $itemType = 0) {
        $row = $this->query()->where('item_id', $itemId)
            ->where('item_type', $itemType)->where('user_id', auth()->id())->delete();
        if (empty($row)) {
            throw new \Exception('无权删除此收藏');
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
            'extra_data' => 'string',
            'created_at' => 'int',
        ]);
    }

}