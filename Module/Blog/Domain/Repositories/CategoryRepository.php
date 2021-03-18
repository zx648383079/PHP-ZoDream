<?php
namespace Module\Blog\Domain\Repositories;


use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\TermModel;
use Zodream\Html\Tree;

class CategoryRepository {

    /**
     * @var bool|TermModel[]
     */
    private static $caches = false;

    private static function boot() {
        if (static::$caches !== false) {
            return;
        }
        static::$caches = [];
        $data = TermModel::query()->get();
        $blog_count = BlogModel::query()->where('parent_id', 0)
            ->groupBy('term_id')
            ->select('term_id,COUNT(*) as count')
            ->pluck('count', 'term_id');
        foreach ($data as $item) {
            $item['blog_count'] = isset($blog_count[$item['id']])
                ? intval($blog_count[$item['id']]) : 0;
            static::$caches[intval($item['id'])] = $item;
        }
    }

    /**
     * @param int $id
     * @return TermModel[]|TermModel|null
     */
    public static function get(int $id = -1) {
        static::boot();
        if (!is_integer($id)) {
            $id = intval($id);
        }
        if ($id < 0) {
            return array_values(static::$caches);
        }
        if ($id < 1 || !isset(static::$caches[$id])) {
            return null;
        }
        return static::$caches[$id];
    }

    /**
     * @return array
     */
    public static function tree() {
        return (new Tree(self::get()))->makeTreeForHtml();
    }

    public static function all() {
        return (new Tree(TermModel::query()
            ->get('id', 'name', 'parent_id')))->makeTreeForHtml();
    }
}