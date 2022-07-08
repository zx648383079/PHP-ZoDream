<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\Model;
use Domain\Model\SearchModel;
use Domain\Repositories\CRUDRepository;
use Module\OnlineTV\Domain\Models\CategoryModel;
use Module\OnlineTV\Domain\Models\MovieModel;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Database\Relation;
use Zodream\Html\Tree;

final class CategoryRepository extends CRUDRepository {

    protected static function query(): SqlBuilder
    {
        return CategoryModel::query();
    }

    protected static function createNew(): Model
    {
        return new CategoryModel();
    }

    public static function getChildren(int $parent = 0, string $extra = '') {
        $data = static::query()->where('parent_id', $parent)->get();
        $extra = explode(',', $extra);
        foreach ($data as $item) {
            if (in_array('recommend', $extra)) {
                $item['recommend_items'] = MovieModel::query()->where('cat_id', $item['id'])
                    ->orderBy('release_date')
                        ->limit(6)->get(MovieRepository::MOVIE_PAGE_FILED);
            }
            if (in_array('new_items', $extra)) {
                $item['new_items'] =
                    MovieModel::query()->where('cat_id', $item['id'])->orderBy('updated_at', 'desc')
                        ->limit(5)->get(MovieRepository::MOVIE_PAGE_FILED);
            }
        }

        return $data;
    }

    public static function toTree(bool $full = false) {
        return new Tree(self::query()->when(!$full, function ($query) {
            $query->select('id', 'name', 'parent_id');
        })->get());
    }

    public static function levelTree(array $excludes = []) {
        $data = self::all(false);
        if (empty($excludes)) {
            return $data;
        }
        return array_filter($data, function ($item) use (&$excludes) {
            if (in_array($item['id'], $excludes)) {
                return false;
            }
            if (in_array($item['parent_id'], $excludes)) {
                $excludes[] = $item['id'];
                return false;
            }
            return true;
        });
    }

    public static function tree() {
        return self::toTree(false)->makeIdTree();
    }

    public static function all(bool $full = false) {
        return self::toTree($full)->makeTreeForHtml();
    }

    public static function recommend() {
        return CategoryModel::limit(6)->get();
    }

    public static function search(string $keywords = '', int|array $id = 0) {
        return SearchModel::searchOption(
            static::query(),
            ['name'],
            $keywords,
            $id === 0 ? [] : compact('id')
        );
    }
}
