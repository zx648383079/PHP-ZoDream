<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\DayLogProvider;
use Domain\Providers\ScoreProvider;
use Domain\Providers\TagProvider;
use Module\Book\Domain\Model\BookBuyLogModel;
use Module\Book\Domain\Model\BookChapterBodyModel;
use Module\Book\Domain\Model\BookChapterModel;
use Module\Book\Domain\Model\BookFullModel;
use Module\Book\Domain\Model\BookHistoryModel;
use Module\Book\Domain\Model\BookModel;
use Module\Book\Domain\Model\BookPageModel;
use Module\Book\Domain\Model\BookRoleModel;
use Module\Book\Domain\Model\BookSourceModel;
use Module\Book\Domain\Model\ListItemModel;
use Zodream\Database\Model\Query;
use Zodream\Database\Query\Builder;
use Zodream\Html\Page;
use Zodream\Html\Tree;

class BookRepository {

    const BASE_KEY = 'book';
    const DEFAULT_COVER = '/assets/images/book_default.jpg';
    const LOG_TYPE_BOOK = 0;
    const LOG_TYPE_LIST = 1;

    const LOG_ACTION_CLICK = 0;
    const LOG_ACTION_COLLECT = 3;
    const LOG_ACTION_AGREE = 1;
    const LOG_ACTION_DISAGREE = 2;

    public static function log(): ActionLogProvider {
        return new ActionLogProvider(self::BASE_KEY);
    }

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

    public static function clickLog(): DayLogProvider {
        return new DayLogProvider(self::BASE_KEY);
    }

    public static function score(): ScoreProvider {
        return new ScoreProvider(self::BASE_KEY);
    }

    /**
     * 前台请求
     * @param array $id
     * @param int $category
     * @param string $keywords
     * @param bool $top
     * @param int $status
     * @param int $author
     * @param int $page
     * @param int $per_page
     * @return \Zodream\Html\Page
     */
    public static function getList($id = [],
                                   int $category = 0,
                                   string $keywords = '',
                                   string|bool $top = false,
                                   int $status = 0,
                                   int $author = 0,
                                   int $page = 1, int $per_page = 20) {
        $query = BookPageModel::with('category', 'author')->ofClassify()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })
            ->when(is_array($id), function ($query) use ($id) {
                $query->whereIn('id', $id);
            })
            ->when($author > 0, function ($query) use ($author) {
                $query->where('author_id', $author);
            })
            ->where('status', 1)
            ->when($status == 1, function ($query) {
                $query->where('over_at', 0);
            })->when($status == 2, function ($query) {
                $query->where('over_at > 0');
            })
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            });
        return !empty($top) ?
            static::sortByClick($query, $top, $page, $per_page)
            : $query->page($per_page);
    }

    protected static function sortByClick(Builder $query, string $type, int $page = 1, int $per_page = 20) {
        $logs = static::clickLogs($type);
        $pager = new Page(count($logs), $per_page, $page);
        if (empty($logs) || $pager->getTotal() < $pager->getStart()) {
            return $pager->setPage([]);
        }
        $logs = array_splice($logs, $pager->getStart(), $pager->getPageSize());
        if (empty($logs)) {
            return $pager->setPage([]);
        }
        $logs = array_column($logs, 'count', 'item_id');
        $book_list = $query->whereIn('id', array_keys($logs))->get();
        foreach ($book_list as $item) {
            $item->click_count = $logs[$item->id];
        }
        usort($book_list, function (BookModel $a, BookModel $b) {
            if ($a->click_count > $b->click_count) {
                return 1;
            }
            if ($a->click_count == $b->click_count) {
                return 0;
            }
            return -1;
        });
        return $pager->setPage($book_list);
    }

    public static function clickLogs(string $type) {
        return cache()->getOrSet('book_top_'.$type, function () use ($type) {
            switch ($type) {
                case 'month':
                    return static::clickLog()->sortByMonth(self::LOG_TYPE_BOOK, self::LOG_ACTION_CLICK);
                case 'week':
                    return static::clickLog()->sortByWeek(self::LOG_TYPE_BOOK, self::LOG_ACTION_CLICK);
                case 'day':
                    return static::clickLog()->sortByDay(self::LOG_TYPE_BOOK, self::LOG_ACTION_CLICK);
            }
            return [];
        }, 3600);
    }

    public static function getManageList(string $keywords = '',
                                         int $category = 0,
                                         int $author = 0,
                                         int $classify = 0,
                                         int $status = -1) {
        return BookPageModel::with('category', 'author')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->when($author > 0, function ($query) use ($author) {
                $query->where('author_id', $author);
            })
            ->where('classify', $classify)
            ->when($status >= 0, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderBy('id', 'desc')->page();
    }

    public static function getSelfList(string $keywords = '', int $category = 0) {
        return BookPageModel::with('category', 'author')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', $category);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->where('user_id', auth()->id())
            ->orderBy('id', 'desc')->page();
    }

    public static function detail(int $id) {
        $model = BookFullModel::find($id);
        if (empty($model)) {
            throw new \Exception('小说不存在');
        }
        if ($model->status != 1) {
            throw new \Exception('小说不存在');
        }
        $_ = $model->category;
        $_ = $model->author;
        $model->on_shelf = HistoryRepository::hasBook($model->id);
        return $model;
    }

    public static function getManage(int $id) {
        $model = BookModel::findOrThrow($id, '小说不存在');
        $_ = $model->category;
        $_ = $model->author;
        return $model;
    }

    public static function getSelf(int $id) {
        $model = BookModel::where('user_id', auth()->id())
            ->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('小说不存在');
        }
        $model->chapters = (new Tree(BookChapterModel::where('book_id', $model->id)
            ->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')
            ->orderBy('id', 'asc')->get()))->makeTree();
        return $model;
    }

    public static function save(array $data) {
        $model = new BookModel();
        if (!$model->load($data)) {
            throw new \Exception('输入数据有误！');
        }
        if ($model->isExist()) {
            throw new \Exception('书籍已存在！');
        }
        $model->autoIsNew();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function saveSelf(array $data) {
        if (isset($data['id']) && $data['id'] > 0 && BookModel::where('user_id', auth()->id())
        ->where('id', $data['id'])->count() < 1) {
            throw new \Exception('操作失败');
        }
        $data['user_id'] = auth()->id();
        $data['author_id'] = AuthorRepository::authAuthor();
        return static::save($data);
    }

    public static function remove(int $id) {
        BookModel::where('id', $id)->delete();
        $ids = BookChapterModel::where('book_id', $id)->pluck('id');
        if (!empty($ids)) {
            BookChapterModel::where('book_id', $id)->delete();
            BookChapterBodyModel::whereIn('id', $ids)->delete();
        }
    }

    public static function chapters(int $book) {
        return (new Tree(BookChapterModel::where('book_id', $book)
            ->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')->get()))->makeTree();
    }

    public static function chapter(int $id, int $book = 0) {
        $chapter = $id > 0 ?
            BookChapterModel::find($id) : BookChapterModel::where('book_id', $book)
                ->orderBy('position', 'asc')
                ->orderBy('created_at', 'asc')
                ->first();
        if (empty($chapter)) {
            throw new \Exception('id 错误！');
        }
        self::clickLog()->add(self::LOG_TYPE_BOOK, $chapter->book_id, 0);
        $data = $chapter->toArray();
        $data['content'] = $chapter->type < 1 ? $chapter->body->content : '';
        $data['previous'] = $chapter->previous;
        $data['next'] = $chapter->next;
        return $data;
    }

    public static function refreshBook() {
        static::deleteNoBookChapter();
        static::refreshBookSize();
    }

    protected static function refreshBookSize() {
        $ids = BookModel::pluck('id');
        foreach ($ids as $id) {
            //$ids = BookChapterModel::where('book_id', $id)->pluck('id');
            //$length = BookChapterBodyModel::whereIn('id', $ids)->sum('char_length(content)');
            static::refreshSize($id);
        }
    }

    public static function refreshPosition(int $book) {
        $data = BookChapterModel::where('book_id', $book)
            ->orderBy('parent_id', 'asc')
            ->orderBy('position', 'asc')
            ->orderBy('created_at', 'asc')->get('id', 'position');
        $position = 0;
        foreach ($data as $item) {
            $position ++;
            if (intval($item['position']) === $position) {
                continue;
            }
            BookChapterModel::where('id', $item['id'])->update([
               'position' => $position
            ]);
        }
    }

    public static function refreshSize(int $book) {
        $length = BookChapterModel::where('book_id', $book)->sum('size');
        BookModel::where('id', $book)
            ->update([
                'size' => $length
            ]);
    }

    protected static function deleteNoBookChapter(): void {
        $ids = BookChapterModel::query()->alias('c')
            ->left('book b', 'b.id', '=', 'c.book_id')
            ->where('b.id')
            ->select('c.id')
            ->pluck();
        if (!empty($ids)) {
            BookChapterModel::whereIn('id', $ids)->delete();
            BookChapterBodyModel::whereIn('id', $ids)->delete();
        }
    }

    public static function isSelf(int $id): bool {
        return BookModel::where('user_id', auth()->id())->where('id', $id)
            ->count() > 0;
    }

    public static function overSelf(int $id) {
        $model = BookModel::where('user_id', auth()->id())->where('id', $id)->first();
        if (empty($model)) {
            throw new \Exception('操作失败');
        }
        $model->over_at = time();
        $model->save();
        return $model;
    }

    public static function checkOpen(int $id) {
        $isOpen = BookModel::isOpen()->where('id', $id)->count() > 0;
        if (!$isOpen) {
            throw new \Exception('书籍不存在');
        }
        return true;
    }

    public static function getHot() {
        return BookModel::isOpen()
            ->limit(4)->pluck('name');
    }

    public static function suggestion(string $keywords = '') {
        return BookModel::isOpen()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->limit(4)->pluck('name');
    }

    /**
     * 整理小说id，及目录id
     */
    public static function sortOut() {
        set_time_limit(0);
        BookModel::refreshPk(function ($old_id, $new_id) {
            foreach ([
                         BookChapterModel::class,
                         BookSourceModel::class,
                         BookHistoryModel::class,
                         BookBuyLogModel::class,
                         BookRoleModel::class,
                         ListItemModel::class
                     ] as $class) {
                /** @var Query $query */
                $query = call_user_func($class.'::query');
                $query->where('book_id', $old_id)->update([
                    'book_id' => $new_id
                ]);
            }
            self::log()->query()->where('item_type', self::LOG_TYPE_BOOK)->where('item_id', $old_id)->update([
                'item_id' => $new_id
            ]);
            self::clickLog()->query()->where('item_type', self::LOG_TYPE_BOOK)->where('item_id', $old_id)->update([
                'item_id' => $new_id
            ]);
            self::clickLog()->dayQuery()
                ->where('item_type', self::LOG_TYPE_BOOK)->where('item_id', $old_id)->update([
                'item_id' => $new_id
            ]);
        });
        BookChapterModel::refreshPk(function ($old_id, $new_id) {
            foreach ([
                         BookHistoryModel::class,
                         BookBuyLogModel::class,
                     ] as $class) {
                /** @var Query $query */
                $query = call_user_func($class.'::query');
                $query->where('chapter_id', $old_id)->update([
                    'chapter_id' => $new_id
                ]);
            }
            BookChapterBodyModel::where('id', $old_id)
                ->update([
                   'id' => $new_id
                ]);
        });
    }
}