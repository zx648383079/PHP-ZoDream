<?php
declare(strict_types=1);
namespace Domain\Providers;

use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Query\Builder;
use Zodream\Database\Schema\Table;
use Zodream\Helpers\Json;

/**
 * 草稿箱
 */
class SketchBoxProvider {

    const SKETCH_TABLE = 'base_sketch_box';

    public function __construct(
        protected int $itemType,
        protected int $maxUndoCount = 1,
    ) {

    }

    public function query(): Builder {
        return DB::table(static::SKETCH_TABLE);
    }

    public function migration(Migration $migration): Migration {
        return $migration->append(static::SKETCH_TABLE, function(Table $table) {
            $table->id();
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id')->default(0);
            $table->uint('user_id');
            $table->column('data')->mediumText();
            $table->string('ip', 120)->default('');
            $table->timestamps();
        });
    }

    public function save(array $data, int $target = 0): void {
        $logList = $this->query()->where('item_type', $this->itemType)
            ->where('item_id', $target)
            ->where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')->get('id');
        $saveData = [
            'item_type' => $this->itemType,
            'item_id' => $target,
            'data' => Json::encode($data),
            'user_id' => auth()->id(),
            'ip' => request()->ip(),
            'updated_at' => time(),
            'created_at' => time(),
        ];
        if (empty($logList) || count($logList) < $this->maxUndoCount) {
            $this->query()->insert($saveData);
            return;
        }
        unset($saveData['created_at']);
        $this->query()->where('id', $logList[$this->maxUndoCount - 1]['id'])
            ->update($saveData);
        if (count($logList) <= $this->maxUndoCount) {
            return;
        }
        $del = array_column(array_slice($logList, $this->maxUndoCount), 'id');
        $this->query()->whereIn('id', $del)->delete();
    }

    /**
     * 获取保存的全部记录
     * @param int $target
     * @return array
     * @throws \Exception
     */
    public function stack(int $target = 0): array {
        return $this->query()->where('item_type', $this->itemType)
            ->where('item_id', $target)
            ->where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->get('id', 'ip', 'updated_at', 'created_at');
    }

    public function get(int $target = 0): array|null {
        $data = $this->query()->where('item_type', $this->itemType)
            ->where('item_id', $target)
            ->where('user_id', auth()->id())
            ->orderBy('updated_at', 'desc')->first('data');
        if (empty($data)) {
            return null;
        }
        return Json::decode($data['data']);
    }

    public function getById(int $id): array|null {
        $data = $this->query()->where('item_type', $this->itemType)
            ->where('id', $id)
            ->where('user_id', auth()->id())->first('data');
        if (empty($data)) {
            return null;
        }
        return Json::decode($data['data']);
    }

    public function remove(int $target = 0): void {
        $this->query()->where('item_type', $this->itemType)
            ->where('item_id', $target)
            ->where('user_id', auth()->id())->delete();
    }

    public function clear(): void {
        $this->query()->where('item_type', $this->itemType)->delete();
    }
}