<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookBuyLogModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Zodream\Html\Page;

class ChapterRepository {
    const TYPE_FREE_CHAPTER = 0;
    const TYPE_VIP_CHAPTER = 1;
    const TYPE_GROUP = 9; // 卷
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
        if ($model->type == ChapterRepository::TYPE_GROUP) {
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
        if (!$model->save(true)) {
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
    public static function applyIsBought(int $bookId, array|Page $items) {
        $idItems = [];
        foreach ($items as $item) {
            if ($item['type'] == 1) {
                $idItems[] = $item['id'];
            }
        }
        // TODO 获取已购买的id
        $boughtItems = empty($idItems) ? [] : BookBuyLogModel::where('book_id', $bookId)
            ->where('user_id', auth()->id())->pluck('chapter_id');
        foreach ($items as $item) {
            $item['is_bought'] = $item['type'] != 1 || in_array($item['id'], $boughtItems);
        }
        return $items;
    }

    public static function isBought(int $bookId, int $chapterId, int $chapterType): bool {
        if ($chapterType === self::TYPE_FREE_CHAPTER || $chapterType === self::TYPE_GROUP) {
            return true;
        }
        if (auth()->guest()) {
            return false;
        }
        return BookBuyLogModel::where('book_id', $bookId)
            ->where('chapter_id', $chapterId)
            ->where('user_id', auth()->id())->count() > 0;
    }

    public static function move(int $id, int $before = 0, int $after = 0) {
        if ($before < 1 && $after < 1) {
            throw new \Exception('请选择定位点');
        }
        if ($before > 0) {
            static::moveBefore($id, $before);
            return;
        }
        static::moveAfter($id, $after);
    }

    public static function moveBefore(int $id, int $before) {
        list($model, $beforeModel) = self::checkPosition($id, $before);
        if ($model->position < $beforeModel->position) {
            BookChapterModel::query()->where('book_id', $model->book_id)
                ->where('position', '>', $model->position)
                ->where('position', '<', $beforeModel->position)
                ->updateDecrement('position');
            BookChapterModel::where('id', $id)->update([
                'position' => $beforeModel->position - 1
            ]);
            return;
        }
        BookChapterModel::query()->where('book_id', $model->book_id)
            ->where('position', '<', $model->position)
            ->where('position', '>=', $beforeModel->position)
            ->updateIncrement('position');
        BookChapterModel::where('id', $id)->update([
            'position' => $beforeModel->position
        ]);
    }

    private static function checkPosition(int $id, int $twoId): array {
        if ($id === $twoId) {
            throw new \Exception('章节错误');
        }
        $model = BookChapterModel::findOrThrow($id, '章节不存在');
        if ($model->position < 1) {
            BookRepository::refreshPosition($model->book_id);
        }
        $model->position = intval(BookChapterModel::where('id', $id)->value('position'));
        $twoModel = BookChapterModel::where('id', $twoId)->where('book_id', $model->book_id)
            ->first('id', 'position');
        if (empty($twoModel)) {
            throw new \Exception('章节不存在');
        }
        return [$model, $twoModel];
    }

    public static function moveAfter(int $id, int $after) {
        list($model, $afterModel) = self::checkPosition($id, $after);
        if ($model->position < $afterModel->position) {
            BookChapterModel::query()->where('book_id', $model->book_id)
                ->where('position', '>', $model->position)
                ->where('position', '<=', $afterModel->position)
                ->updateDecrement('position');
            BookChapterModel::where('id', $id)->update([
                'position' => $afterModel->position
            ]);
            return;
        }
        BookChapterModel::query()->where('book_id', $model->book_id)
            ->where('position', '<', $model->position)
            ->where('position', '>', $afterModel->position)
            ->updateIncrement('position');
        BookChapterModel::where('id', $id)->update([
            'position' => $afterModel->position + 1
        ]);
    }
}