<?php
declare(strict_types=1);
namespace Domain\Providers;

use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Model\Model;
use Zodream\Database\Query\Builder;
use Zodream\Database\Schema\Table;
use Zodream\Helpers\Arr;

class ActionLogProvider {

    protected string $tableName;

    public function __construct(
        protected string $key
    ) {
        $this->tableName = $this->key.'_log';
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
            $table->uint('action');
            $table->string('ip', 120)->default('');
            $table->timestamp(Model::CREATED_AT);
        });
    }

    /**
     * 切换记录
     * @param int $type
     * @param int $action
     * @param int $id
     * @param array|int|null $searchAction
     * @return int // {0: 取消，1: 更新为，2：新增}
     * @throws \Exception
     */
    public function toggleLog(int $type, int $action,
                                     int $id,
                                     array|int|null $searchAction = null): int {
        if (auth()->guest()) {
            return 0;
        }
        if (empty($searchAction)) {
            $searchAction = $action;
        }
        $log = $this->query()->where('user_id', auth()->id())
            ->where('item_type', $type)
            ->when(is_array($searchAction), function ($query) use ($searchAction) {
                if (count($searchAction) === 1) {
                    $query->where('action', current($searchAction));
                    return;
                }
                $query->whereIn('action', $searchAction);
            }, function ($query) use ($searchAction) {
                $query->where('action', $searchAction);
            })
            ->where('item_id', $id)
            ->first();
        if (!empty($log) && intval($log['action']) === $action) {
            $this->remove(intval($log['id']));
            return 0;
        }
        if (!empty($log)) {
            $this->update(intval($log['id']), [
                'action' => $action,
                'created_at' => time()
            ]);
            return 1;
        }
        $this->insert([
            'item_type' => $type,
            'item_id' => $id,
            'action' => $action,
        ]);
        return 2;
    }


    public function getAction(int $type, int $id, array $onlyAction = []): int|null {
        if (auth()->guest()) {
            return null;
        }
        $log = $this->query()->where('user_id', auth()->id())
            ->where('item_type', $type)->where('item_id', $id)
            ->when(!empty($onlyAction), function ($query) use ($onlyAction) {
                if (count($onlyAction) === 1) {
                    $query->where('action', current($onlyAction));
                    return;
                }
                $query->whereIn('action', $onlyAction);
            })
            ->first('action');
        return !empty($log) ? intval($log['action']) : null;
    }

    /**
     * 获取操作的总记录
     * @param int $type
     * @param int $action
     * @param int $id
     * @return int
     */
    public function count(int $type, int $action, int $id): int {
        return static::query()->where('item_id', $id)
            ->where('item_type', $type)
            ->where('action', $action)->count();
    }

    public function has(int $type, int $id, int $action = 0): bool {
        if (auth()->guest()) {
            return false;
        }
        return $this->query()->where('user_id', auth()->id())
            ->where('item_type', $type)->where('item_id', $id)->where('action', $action)
            ->count() > 0;
    }

    public function insert(array $data): void {
        if (!isset($data['ip'])) {
            $data['ip'] = request()->ip();
        }
        if (!isset($data['created_at'])) {
            $data['created_at'] = time();
        }
        if (!isset($data['user_id'])) {
            $data['user_id'] = auth()->id();
        }
        $id = $this->query()->insert($data);
        if (empty($id)) {
            throw new \Exception('insert log error');
        }
    }

    public function update(int $id, array $data): void {
        $this->query()->where('id', $id)->update($data);
    }

    public function remove(int $id): void {
        $this->query()->where('id', $id)->delete();
    }

    public function format(array $data): array {
        if (empty($data)) {
            return [];
        }
        return Arr::toRealArr($data, [
            'id' => 'int',
            'item_type' => 'int',
            'item_id' => 'int',
            'user_id' => 'int',
            'action' => 'int',
            'created_at' => 'int',
        ]);
    }

}