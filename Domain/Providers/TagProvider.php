<?php
declare(strict_types=1);
namespace Domain\Providers;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Zodream\Database\DB;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Query\Builder;
use Zodream\Database\Relation;
use Zodream\Database\Schema\Table;

class TagProvider {

    protected string $mainTableName;
    protected string $linkTableName;

    public function __construct(
        protected string $key
    ) {
        $this->mainTableName = $this->key.'_tag';
        $this->linkTableName = $this->key.'_tag_link';
    }

    public function tagQuery(): Builder {
        return DB::table($this->mainTableName);
    }

    public function linkQuery(): Builder {
        return DB::table($this->linkTableName);
    }

    public function migration(Migration $migration): Migration {
        return $migration->append($this->mainTableName, function(Table $table) {
            $table->id();
            $table->string('name', 20);
        })->append($this->linkTableName, function(Table $table) {
            $table->uint('tag_id');
            $table->uint('target_id');
        });
    }


    public function remove(int $id): void {
        $this->tagQuery()->where('id', $id)->delete();
        $this->linkQuery()->where('tag_id', $id)->delete();
    }

    /**
     * 保存标签并获取标签的id
     * @param string|array $name
     * @param array $append 创建时需要追加的
     * @return array
     */
    public function save(string|array $name, array $append = []): array {
        $items = [];
        $idItems = [];
        foreach ((array)$name as $item) {
            if (is_array($item)) {
                if (isset($item['id']) && $item['id'] > 0) {
                    $idItems[] = $item['id'];
                    continue;
                }
                $item = $item['name'] ?? null;
            }
            if (empty($item)) {
                continue;
            }
            if (in_array($name, $items)) {
                continue;
            }
            $items[] = $item;
        }
        $exist = $this->tagQuery()->whereIn('name', $items)
            ->pluck('id', 'name');
        foreach ($items as $name) {
            if (isset($exist[$name])) {
                $idItems[] = $exist[$name];
                continue;
            }
            $id = $this->tagQuery()->insert(array_merge(
                $append,
                [
                    'name' => $name,
                ]
            ));
            if ($id > 0) {
                $idItems[] = $id;
            }
        }
        return $idItems;
    }

    public function getList(string $keywords = '', int $perPage = 20) {
        return $this->tagQuery()->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], false, '', $keywords);
        })->page($perPage);
    }

    /**
     * @param string $keywords
     * @param int $count
     * @return string[]
     */
    public function suggest(string $keywords = '', int $count = 5): array {
        return $this->tagQuery()->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, ['name'], false, '', $keywords);
        })->limit($count)->pluck('name');
    }

    public function all() {
        return $this->tagQuery()->get(['id', 'name']);
    }

    public function getTags(int $target): array {
        $tagId = $this->linkQuery()->where('target_id', $target)->pluck('tag_id');
        if (empty($tagId)) {
            return [];
        }
        return $this->tagQuery()->whereIn('id', $tagId)->get();
    }

    public function bindRelation(string $linkName): Relation {
        return Relation::parse([
            'query' => $this->linkQuery(),
            'type' => Relation::TYPE_MANY,
            'link' => [
                $linkName => 'target_id'
            ],
            'relation' => [
                [
                    'query' => $this->tagQuery(),
                    'type' => Relation::TYPE_ONE,
                    'link' => [
                        'tag_id' => 'id'
                    ],
                ]
            ]
        ]);
    }

    public function getManyTags(array $target): array {
        $data = $this->linkQuery()->whereIn('target_id', $target)->get();
        if (empty($data)) {
            return [];
        }
        $tagId = array_unique(array_column($data, 'tag_id'));
        $tags = $this->tagQuery()->whereIn('id', $tagId)->get();
        $tags = array_column($tags, null, 'id');
        $items = [];
        foreach ($target as $id) {
            if (isset($items[$id])) {
                continue;
            }
            $tagItems = [];
            foreach ($data as $item) {
                if ($item['target_id'] == $id && isset($tags[$item['tag_id']])) {
                    $tagItems[] = $tags[$item['tag_id']];
                }
            }
            $items[$id] = $tagItems;
        }
        return $items;
    }
    /**
     * 根据关键词搜索标签表，并根据中间表找到链接的id集合
     * @param string $keywords
     * @return array
     */
    public function searchTag(string $keywords = ''): array {
        if (empty($keywords)) {
            return $this->linkQuery()
                ->selectRaw('distinct target_id')
                ->pluck('target_id');
        }
        $tagId = SearchModel::searchWhere($this->tagQuery(),
            'name', false, '', $keywords)
            ->pluck('id');
        if (empty($tagId)) {
            return [];
        }
        return $this->linkQuery()->whereIn('tag_id', $tagId)
            ->selectRaw('distinct target_id')
            ->pluck('target_id');
    }

    /**
     * 关联标签
     * @param int $target 其他表的主键值
     * @param string|array $tags 标签
     * @param array $append
     * @param callable|null $afterBind ($tagId: int[], $add: int[], $del: int[])
     * @throws \Exception
     */
    public function bindTag(int $target,
                                   string|array $tags,
                                   array $append = [], callable|null $afterBind = null) {
        $tagId = $this->save($tags, $append);
        if (empty($tagId)) {
            return;
        }
        list($add, $_, $del) = ModelHelper::splitId(
            $tagId,
            $this->linkQuery()->where('target_id', $target)
                ->pluck('tag_id'),
        );
        if (!empty($del)) {
            $this->linkQuery()->where('target_id', $target)
                ->whereIn('tag_id', $del)->delete();
        }
        if (!empty($add)) {
            $this->linkQuery()->insert(array_map(function ($tag_id) use ($target) {
                return [
                    'tag_id' => $tag_id,
                    'target_id' => $target,
                ];
            }, $add));
        }
        if ($afterBind) {
            call_user_func($afterBind, $tagId, $add, $del);
        }
    }

    public function removeLink(int $target) {
        $this->linkQuery()->where('target_id', $target)->delete();
    }
}