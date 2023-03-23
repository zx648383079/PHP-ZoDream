<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\OnlineTV\Domain\Models\MusicListItemModel;
use Module\OnlineTV\Domain\Models\MusicListModel;

class ListRepository {

    public static function getList(string $keywords = '') {
        return MusicListModel::with('user')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->orderBy('created_at', 'desc')->page();
    }

    public static function detail(int $id) {
        $model = MusicListModel::find($id);
        if (empty($model)) {
            throw new \Exception('歌单不存在');
        }
        $items = MusicListItemModel::with('music')->where('list_id', $id)->get();
        $data = $model->toArray();
        $data['user'] = $model->user;
        $data['items'] = $items;
        return $data;
    }

    public static function save(array $data, array $items) {
        if (empty($items)) {
            throw new \Exception('请选择书籍');
        }
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? MusicListModel::findWithAuth($id) : new MusicListModel();
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $model->user_id = auth()->id();
        if (!$model->load($data) || !$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        $exist = [];
        if ($id > 0) {
            $exist =  MusicListItemModel::where('list_id', $model->id)->pluck('music_id');
        }
        $add = [];
        $update = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                $item = [
                    'music_id' => intval($item)
                ];
            } else {
                $item['music_id'] = intval($item['music_id']);
            }
            if (!isset($item['music_id']) || $item['music_id'] < 1) {
                continue;
            }
            if (!in_array($item['music_id'], $exist)) {
                $add[] = [
                    'list_id' => $model->id,
                    'music_id' => $item['music_id'],
                ];
                continue;
            }
            $update[] = $item['music_id'];
        }
        $del = array_diff($exist, $update);
        if (!empty($del)) {
            MusicListItemModel::query()->where('list_id', $model->id)
                ->whereIn('music_id', $del)
                ->delete();
        }
        if (!empty($add)) {
            MusicListItemModel::query()->insert($add);
        }
        return $model;
    }

    public static function remove(int $id) {
        $model = MusicListModel::findWithAuth($id);
        if (empty($model)) {
            throw new \Exception('书单不存在');
        }
        $model->delete();
        MusicListItemModel::where('list_id', $model->id)->delete();
    }

}