<?php
declare(strict_types=1);
namespace Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Zodream\Database\Contracts\SqlBuilder;

abstract class TagRepository {

    protected static string $idKey = 'id';
    protected static string $nameKey = 'name';

    abstract protected static function query(): SqlBuilder;

    /**
     * 保存标签并获取标签的id
     * @param string|array $name
     * @param array $append 创建时需要追加的
     * @return array
     */
    public static function save(string|array $name, array $append = []): array {
        $items = [];
        $idItems = [];
        foreach ((array)$name as $item) {
            if (is_array($item)) {
                if (isset($item[static::$idKey]) && $item[static::$idKey] > 0) {
                    $idItems[] = $item[static::$idKey];
                    continue;
                }
                $item = isset($item[static::$nameKey]) ? $item[static::$nameKey] : null;
            }
            if (empty($item)) {
                continue;
            }
            if (in_array($name, $items)) {
                continue;
            }
            $items[] = $item;
        }
        $exist = static::query()->whereIn(static::$nameKey, $items)
            ->pluck(static::$idKey, static::$nameKey);
        foreach ($items as $name) {
            if (isset($exist[$name])) {
                $idItems[] = $exist[$name];
                continue;
            }
            $id = static::query()->insert(array_merge(
                $append,
                [
                    static::$nameKey => $name,
                ]
            ));
            if ($id > 0) {
                $idItems[] = $id;
            }
        }
        return $idItems;
    }

    public static function getList(string $keywords = '', int $perPage = 20) {
        return static::query()->when(!empty($keywords), function ($query) use ($keywords) {
            SearchModel::searchWhere($query, [static::$nameKey], false, '', $keywords);
        })->page($perPage);
    }

    public static function allList() {
        return static::query()->get([static::$idKey, static::$nameKey]);
    }

    /**
     * 根据关键词搜索标签表，并根据中间表找到链接的id集合
     * @param SqlBuilder $builder 中间表的查询
     * @param string $linkKey 中间表连接其他表的字段
     * @param string $keywords
     * @param string $tagKey 中间表连接标签表的字段
     * @return array
     */
    public static function searchTag(SqlBuilder $builder,
                                          string $linkKey,
                                          string $keywords = '',
                                          string $tagKey = 'tag_id'): array {
        if (empty($keywords)) {
            return $builder
                ->selectRaw('distinct '.$linkKey)
                ->pluck($linkKey);
        }
        $tagId = SearchModel::searchWhere(static::query(),
            static::$nameKey, false, '', $keywords)
            ->pluck(static::$idKey);
        if (empty($tagId)) {
            return [];
        }
        return $builder->whereIn($tagKey, $tagId)
            ->selectRaw('distinct '.$linkKey)
            ->pluck($linkKey);
    }

    /**
     * 关联标签
     * @param SqlBuilder $builder 中间表的查询
     * @param int $lindId 其他表的主键值
     * @param string $linkKey 中间表连接其他表的字段
     * @param string|array $tags 标签
     * @param array $append
     * @param string $tagKey 中间表连接标签表的字段
     */
    public static function bindTag(SqlBuilder $builder,
                                   int $lindId,
                                   string $linkKey,
                                   string|array $tags,
                                   array $append = [],
                                   string $tagKey = 'tag_id') {
        $tagId = static::save($tags, $append);
        if (empty($tagId)) {
            return;
        }
        list($add, $_, $del) = ModelHelper::splitId(
            $tagId,
            (clone $builder)->where($linkKey, $lindId)
                ->pluck($tagKey),
        );
        if (!empty($del)) {
            (clone $builder)->where($linkKey, $lindId)
                ->whereIn($tagKey, $del)->delete();
        }
        if (!empty($add)) {
            (clone $builder)->insert(array_map(function ($tag_id) use ($lindId, $linkKey, $tagKey) {
                return [
                    $tagKey => $tag_id,
                    $linkKey => $lindId,
                ];
            }, $add));
        }
        static::onAfterBind($tagId, $add, $del);
    }

    /**
     * 绑定标签成功后的操作
     * @param array $tagId
     * @param array $addTag
     * @param array $delTag
     */
    protected static function onAfterBind(array $tagId, array $addTag, array $delTag) {

    }
}