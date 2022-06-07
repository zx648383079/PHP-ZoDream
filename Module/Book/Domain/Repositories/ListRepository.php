<?php
declare(strict_types=1);
namespace Module\Book\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Book\Domain\Model\BookListModel;
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
        $model->is_collected = self::hasLog(BookRepository::LOG_TYPE_LIST,
            BookRepository::LOG_ACTION_COLLECT, $model->id);
        $items = ListItemModel::with('book')->where('list_id', $id)->get();
        foreach ($items as $item) {
            $item->is_agree = self::hasLog(BookRepository::LOG_TYPE_LIST, [BookRepository::LOG_ACTION_AGREE, BookRepository::LOG_ACTION_DISAGREE], $id);
            $item->on_shelf = HistoryRepository::hasBook($item->book_id);
        }
        $_ = $model->user;
        $model->items = $items;
        BookListModel::query()->where('id', $model->id)
            ->updateIncrement('click_count', 1);
        return $model;
    }

    private static function hasLog(int $type, array|int $action, int $id) {
        $res = BookRepository::log()->getAction($type, $id, (array)$action);
        return is_array($action) ? $res : !is_null($res);
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

    public static function collect(int $id) {
        $model = BookListModel::find($id);
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $res = BookRepository::log()->toggleLog(BookRepository::LOG_TYPE_LIST,
            BookRepository::LOG_ACTION_COLLECT, $id);
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
        $res = BookRepository::log()->toggleLog(BookRepository::LOG_TYPE_LIST,
            BookRepository::LOG_ACTION_AGREE, $id,
            [BookRepository::LOG_ACTION_AGREE, BookRepository::LOG_ACTION_DISAGREE]);
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
        $res = BookRepository::log()->toggleLog(BookRepository::LOG_TYPE_LIST,
            BookRepository::LOG_ACTION_DISAGREE, $id,
            [BookRepository::LOG_ACTION_AGREE, BookRepository::LOG_ACTION_DISAGREE]);
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