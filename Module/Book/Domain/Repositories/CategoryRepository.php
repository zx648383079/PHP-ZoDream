<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Module\Book\Domain\Model\BookCategoryModel;
use Module\Book\Domain\Model\BookModel;
use Zodream\Database\Contracts\SqlBuilder;

class CategoryRepository extends CRUDRepository {
    public static function all() {
        return BookCategoryModel::orderBy('id', 'asc')->get();
    }

    public static function getList(string $keywords = '') {
        $items = self::all();
        foreach ($items as $item) {
            $thumb = BookModel::where('cat_id', $item->id)->value('cover');
            if (empty($thumb)) {
                $thumb = BookRepository::DEFAULT_COVER;
            }
            $item['thumb'] = url()->asset($thumb);
            $item['book_count'] = BookModel::where('cat_id', $item->id)->count();
        }
        return $items;
    }

    public static function get(int $id) {
        return BookCategoryModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = BookCategoryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        BookCategoryModel::where('id', $id)->delete();
    }

    protected static function query(): SqlBuilder
    {
        return BookCategoryModel::query();
    }

    protected static function createNew(): Model
    {
        return new BookCategoryModel();
    }
}