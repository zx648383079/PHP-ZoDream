<?php
namespace Module\Book\Domain\Repositories;

use Module\Book\Domain\Model\BookListModel;
use Module\Book\Domain\Model\ListItemModel;

class ListRepository {

    public static function save(array $data) {
        if (!isset($data['items']) || empty($data['items'])) {
            throw new \Exception('请选择书籍');
        }
        $id = isset($data['id']) ? $data['id'] : 0;
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
                    'remark' => isset($item['remark']) ? $item['remark'] : '',
                    'star' => isset($item['star']) ? $item['star'] : 10,
                ];
                continue;
            }
            ListItemModel::where('list_id', $model->id)->where('id', $item['id'])
                ->update([
                    'book_id' => $item['book_id'],
                    'remark' => isset($item['remark']) ? $item['remark'] : '',
                    'star' => isset($item['star']) ? $item['star'] : 10,
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
        return $model;
    }
}