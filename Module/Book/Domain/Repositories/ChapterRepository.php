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
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = BookChapterModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        BookRepository::refreshSize($model->book_id);
        return $model;
    }

    public static function remove(int $id) {
        $model = BookChapterModel::find($id);
        if (empty($model)) {
            return;
        }
        $model->delete();
        BookChapterBodyModel::where('id', $id)->delete();
        BookRepository::refreshSize($model->book_id);
    }

    public static function getSelf(int $id) {
        $model = BookChapterModel::findOrThrow($id, '章节不存在');
        if (!BookRepository::isSelf($model->book_id)) {
            throw new \Exception('操作无效');
        }
        if ($model->type > 0) {
            return $model;
        }
        $model->content = $model->body->content;
        return $model;
    }

    public static function saveSelf(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = BookChapterModel::findOrNew($id);
        $model->load($data);
        if (!BookRepository::isSelf($model->book_id)) {
            throw new \Exception('操作无效');
        }
        if ($model->isNewRecord && $model->position < 1) {
            $model->position = intval(BookChapterModel::query()->where('book_id', $model->book_id)->max('position')) + 1;
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        BookRepository::refreshSize($model->book_id);
        return $model;
    }

    public static function removeSelf(int $id) {
        $model = BookChapterModel::find($id);
        if (empty($model)) {
            return;
        }
        if (!BookRepository::isSelf($model->book_id)) {
            throw new \Exception('操作无效');
        }
        $model->delete();
        BookChapterBodyModel::where('id', $id)->delete();
        BookRepository::refreshSize($model->book_id);
    }

    /**
     * 批量设置是否已购买
     * @param $items
     * @return mixed
     */
    public static function applyIsBought($items) {
        $idItems = [];
        foreach ($items as $item) {
            if ($item['type'] < 1 && $item['price'] > 0) {
                $idItems[] = $item['id'];
            }
        }
        // TODO 获取已购买的id
        $boughtItems = [];
        foreach ($items as $item) {
            $item['is_bought'] = $item['type'] > 0 || $item['price'] <= 0 || in_array($item['id'], $boughtItems);
        }
        return $items;
    }
}