<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\BookLogModel;
use Module\Book\Domain\Model\ListItemModel;

class ListRepository {

    public static function getList(string $keywords = '') {
        return BookListModel::with('user', 'items')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->orderBy('created_at', 'desc')->page();
    }

    public static function detail(int $id) {
        $model = BookListModel::find($id);
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $model->is_collected = self::hasLog(BookLogModel::TYPE_LIST,
                BookLogModel::ACTION_COLLECT, $model->id);
        $items = ListItemModel::with('book')->where('list_id', $id)->get();
        foreach ($items as $item) {
            $item->is_agree = self::hasLog(BookLogModel::TYPE_LIST, [BookLogModel::ACTION_AGREE, BookLogModel::ACTION_DISAGREE], $id);
            $item->on_shelf = HistoryRepository::hasBook($item->book_id);
        }
        $_ = $model->user;
        $model->items = $items;
        BookListModel::query()->where('id', $model->id)
            ->updateIncrement('click_count', 1);
        return $model;
    }

    private static function hasLog($type, $action, $id) {
        if (auth()->guest()) {
            return is_array($action) ? 0 : false;
        }
        $query = BookLogModel::where('user_id', auth()->id())
            ->where('item_type', $type)
            ->where('item_id', $id);
        if (!is_array($action)) {
            return $query->where('action', $action)->count() > 0;
        }
        $log = $query->whereIn('action', $action)->first(['action']);
        if (empty($log)) {
            return 0;
        }
        return array_search(intval($log->action), $action, true) + 1;
    }

    public static function save(array $data) {
        if (!isset($data['items']) || empty($data['items'])) {
            throw new \Exception('请选择书籍');
        }
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? BookListModel::findWithAuth($id) : new BookListModel();
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $model->user_id = auth()->id();
        if (!$model->load($data) || !$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        $exist = [];
        if ($id > 0) {
            $exist = ListItemModel::where('list_id', $model->id)->pluck('id');
        }
        $add = [];
        $update = [];
        foreach ($data['items'] as $item) {
            if (!isset($item['book_id']) || $item['book_id'] < 1) {
                continue;
            }
            if (!isset($item['id']) || $item['id'] < 1 || !in_array($item['id'], $exist)) {
                $add[] = [
                    'list_id' => $model->id,
                    'book_id' => $item['book_id'],
                    'remark' => $item['remark'] ?? '',
                    'star' => $item['star'] ?? 10,
                ];
                continue;
            }
            ListItemModel::where('list_id', $model->id)->where('id', $item['id'])
                ->update([
                    'book_id' => $item['book_id'],
                    'remark' => $item['remark'] ?? '',
                    'star' => $item['star'] ?? 10,
                ]);
            $update[] = $item['id'];
        }
        $del = array_diff($exist, $update);
        if (!empty($del)) {
            ListItemModel::query()->where('list_id', $model->id)
                ->whereIn('id', $del)
                ->delete();
        }
        if (!empty($add)) {
            ListItemModel::query()->insert($add);
        }
        $model->book_count = ListItemModel::where('list_id', $model->id)
            ->count();
        $model->save();
        return $model;
    }

    public static function remove(int $id) {
        $model = BookListModel::findWithAuth($id);
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $model->delete();
        ListItemModel::where('list_id', $model->id)->delete();
    }

    /**
     * 切换记录
     * @param int $type
     * @param int $action
     * @param int $id
     * @param array|int|null $searchAction
     * @return int {0: 取消，1: 更新为，2：新增}
     * @throws \Exception
     */
    public static function toggleLog(int $type, int $action, int $id, array|int|null $searchAction = null): int {
        if (empty($searchAction)) {
            $searchAction = $action;
        }
        $log = BookLogModel::where('user_id', auth()->id())
            ->where('item_type', $type)
            ->when(is_array($searchAction), function ($query) use ($searchAction) {
                $query->whereIn('action', $searchAction);
            }, function ($query) use ($searchAction) {
                $query->where('action', $searchAction);
            })
            ->where('item_id', $id)
            ->first();
        if (!empty($log) && $log->action === $action) {
            $log->delete();
            return 0;
        }
        if (!empty($log)) {
            $log->action = $action;
            $log->created_at = time();
            $log->save();
            return 1;
        }
        BookLogModel::createOrThrow([
            'item_type' => $type,
            'item_id' => $id,
            'action' => $action,
            'user_id' => auth()->id()
        ]);
        return 2;
    }

    public static function collect(int $id) {
        $model = BookListModel::find($id);
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $res = self::toggleLog(BookLogModel::TYPE_LIST, BookLogModel::ACTION_COLLECT, $id);
        if ($res > 0) {
            $model->collect_count ++;
            $model->is_collected = true;
        } else {
            $model->collect_count --;
            $model->is_collected = false;
        }
        $model->save();
        return $model;
    }

    public static function agree(int $id) {
        $model = ListItemModel::find($id);
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $res = self::toggleLog(BookLogModel::TYPE_LIST,
            BookLogModel::ACTION_AGREE, $id,
            [BookLogModel::ACTION_AGREE, BookLogModel::ACTION_DISAGREE]);
        if ($res < 1) {
            $model->agree_count --;
            $model->agree_type = 0;
        } elseif ($res == 1) {
            $model->agree_count ++;
            $model->disagree_count --;
            $model->agree_type = 1;
        } elseif ($res == 2) {
            $model->agree_count ++;
            $model->agree_type = 1;
        }
        $model->save();
        return $model;
    }

    public static function disagree(int $id) {
        $model = ListItemModel::find($id);
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $res = self::toggleLog(BookLogModel::TYPE_LIST,
            BookLogModel::ACTION_DISAGREE, $id,
            [BookLogModel::ACTION_AGREE, BookLogModel::ACTION_DISAGREE]);
        if ($res < 1) {
            $model->disagree_count --;
            $model->agree_type = 0;
        } elseif ($res == 1) {
            $model->agree_count --;
            $model->disagree_count ++;
            $model->agree_type = 2;
        } elseif ($res == 2) {
            $model->disagree_count ++;
            $model->agree_type = 2;
        }
        $model->save();
        return $model;
    }
}