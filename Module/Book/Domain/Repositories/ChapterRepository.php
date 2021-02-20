<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;

class ChapterRepository {
    public static function getList(int $book, string $keywords = '') {
        return BookChapterModel::where('book_id', $book)
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')->page();
    }

    public static function get(int $id) {
        $chapter = BookChapterModel::find($id);
        if (empty($chapter)) {
            throw new \Exception('id 错误！');
        }
        $chapter->content = $chapter->body->content;
        return $chapter;
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = BookChapterModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        $model = BookChapterModel::find($id);
        $model->delete();
        BookChapterBodyModel::where('id', $id)->delete();
    }
}