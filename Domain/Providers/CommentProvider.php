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
use Zodream\Helpers\Json;
use Zodream\Html\Page;

class CommentProvider {

    const LOG_TYPE_COMMENT = 6;
    const LOG_ACTION_AGREE = 1;
    const LOG_ACTION_DISAGREE = 2;

    protected string $tableName;
    protected ActionLogProvider $logger;

    public function __construct(
        protected string $key
    ) {
        $this->tableName = $key.'_comment';
        $this->logger = new ActionLogProvider($this->key);
    }

    public function query(): Builder {
        return DB::table($this->tableName);
    }

    public function migration(Migration $migration): Migration {
        return $this->logger->migration($migration)
            ->append($this->tableName, function(Table $table) {
            $table->id();
            $table->string('content');
            $table->string('extra_rule', 300)->default('')
                ->comment('内容的一些附加规则');
            $table->uint('parent_id');
            $table->uint('user_id')->default(0);
            $table->uint('target_id');
            $table->uint('agree_count')->default(0);
            $table->uint('disagree_count')->default(0);
            $table->uint('status', 1)->default(0)->comment('审核状态');
            $table->timestamp(Model::CREATED_AT);
        });
    }


    /**
     * @param string $keywords
     * @param int $user
     * @param int $target
     * @return Page<array>
     */
    public function search(string $keywords = '',
                           int $user = 0,
                           int $target = 0,
                           int $parentId = -1,
                           string $sort = 'id', string $order = 'desc'): Page {
        list($sort, $order) = SearchModel::checkSortOrder($sort, $order, [
            'id',
            'created_at',
            'agree_count'
        ]);
        $page = $this->query()->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })
            ->when($target > 0, function ($query) use ($target) {
                $query->where('target_id', $target);
            })
            ->when($parentId >= 0, function ($query) use ($parentId) {
                $query->where('parent_id', $parentId);
            })
            ->when(!empty($keywords), function ($query) use ($keywords) {
                SearchModel::searchWhere($query, ['content'], false, '', $keywords);
            })->orderBy($sort, $order)->page();
        $data = $page->getPage();
        if (empty($data)) {
            return $page;
        }
        $data = Relation::create($this->formatList($data), [
            'user' => Relation::make(UserSimpleModel::query(), 'user_id', 'id')
        ]);
        $page->setPage($data);
        return $page;
    }

    protected function formatList(array $data) {
        $idItems = array_column($data, 'id');
        $count = $this->query()->whereIn('parent_id', $idItems)
            ->groupBy('parent_id')
            ->selectRaw('COUNT(*) as count,parent_id as id')->get();
        $count = array_column($count, 'count', 'id');
        foreach ($data as $k => $item) {
            $val = $this->format($item);
            $val['reply_count'] = isset($count[$item['id']]) ? intval($count[$item['id']]) : 0;
            $item['agree_type'] = $this->getAgreeType($val['id']);
            $data[$k] = $val;
        }
        return $data;
    }

    public function remove(int $id) {
        $this->query()->where('id', $id)->delete();
    }

    public function insert(array $data): int {
        if (isset($data['extra_rule']) && is_array($data['extra_rule'])) {
            $data['extra_rule'] = Json::encode($data['extra_rule']);
        }
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

    public function save(array $data): array {
        $data['user_id'] = auth()->id();
        $data['created_at'] = time();
        $data['id'] = $this->insert($data);
        $data['user'] = UserSimpleModel::converterFrom(auth()->user());
        return $data;
    }

    public function removeByTarget(int $id) {
        $this->query()->where('target_id', $id)->delete();
    }

    public function removeBySelf(int $id) {
        $row = $this->query()->where('id', $id)->where('user_id', auth()->id())->delete();
        if (empty($row)) {
            throw new \Exception('无权删除此评论');
        }
    }

    public function agree(int $id) {
        $model = $this->get($id);
        if (!$model) {
            throw new \Exception('评论不存在');
        }
        $res = $this->logger->toggleLog(self::LOG_TYPE_COMMENT,
            self::LOG_ACTION_AGREE, $id,
            [self::LOG_ACTION_AGREE, self::LOG_ACTION_DISAGREE]);
        if ($res < 1) {
            $model['agree_count'] --;
            $model['agree_type'] = 0;
        } elseif ($res == 1) {
            $model['agree_count'] ++;
            $model['disagree_count'] --;
        } elseif ($res == 2) {
            $model['agree_count']++;
        }
        $this->update($id, [
            'agree_count' => $model['agree_count'],
            'disagree_count' => $model['disagree_count'],
        ]);
        if ($res > 0) {
            $model['agree_type'] = self::LOG_ACTION_AGREE;
        }
        return $model;
    }

    public function disagree(int $id) {
        $model = $this->get($id);
        if (!$model) {
            throw new \Exception('评论不存在');
        }
        $res = $this->logger->toggleLog(self::LOG_TYPE_COMMENT,
            self::LOG_ACTION_DISAGREE, $id,
            [self::LOG_ACTION_AGREE, self::LOG_ACTION_DISAGREE]);
        if ($res < 1) {
            $model['disagree_count'] --;
        } elseif ($res == 1) {
            $model['agree_count'] --;
            $model['disagree_count'] ++;
        } elseif ($res == 2) {
            $model['disagree_count'] ++;
        }
        $this->update($id, [
            'agree_count' => $model['agree_count'],
            'disagree_count' => $model['disagree_count'],
        ]);
        if ($res > 0) {
            $model['agree_type'] = self::LOG_ACTION_DISAGREE;
        }
        return $model;
    }

    /**
     * 获取用户同意的类型
     * @param int $comment
     * @return int
     * @throws \Exception
     */
    public function getAgreeType(int $comment): int {
        if (auth()->guest()) {
            return 0;
        }
        $log = $this->logger->getAction(self::LOG_TYPE_COMMENT, $comment,
            [self::LOG_ACTION_AGREE, self::LOG_ACTION_DISAGREE]);
        return is_null($log) ? 0 : $log;
    }

    public function format(array $data): array {
        if (empty($data)) {
            return [];
        }
        return Arr::toRealArr($data, [
            'id' => 'int',
            'target_id' => 'int',
            'user_id' => 'int',
            'extra_rule' => 'array',
            'parent_id' => 'int',
            'agree_count' => 'int',
            'disagree_count' => 'int',
            'created_at' => 'int',
        ]);
    }

}