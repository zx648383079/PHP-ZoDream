<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Domain\Model\ModelHelper;
use Domain\Model\SearchModel;
use Module\Auth\Domain\Helpers;
use Module\Contact\Domain\Model\FeedbackModel;
use Zodream\Html\Page;

class FeedbackRepository {

    public static function manageList(string $keywords = '') {
        return FeedbackModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'email', 'content']);
        })->orderBy([
            'status' => 'asc',
            'id' => 'desc'
        ])->page();
    }

    /**
     * 允许前台显示的反馈内容
     * @param string $keywords
     * @return Page
     */
    public static function getList(string $keywords = '', int $perPage = 20) {
        $items = FeedbackModel::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'content']);
        })->where('open_status', 1)->orderBy('id', 'desc')->page($perPage);
        foreach ($items as $item) {
            $item['name'] = Helpers::hideName($item['name']);
            $item['email'] = Helpers::hideEmail($item['email']);
            $item['phone'] = Helpers::hideTel($item['phone']);
        }
        return $items;
    }

    public static function get(int $id) {
        return FeedbackModel::findOrThrow($id, '数据有误');
    }

    public static function change(int $id, array $data) {
        $model = static::get($id);
        $maps = ['status', 'open_status'];
        foreach ($data as $action => $val) {
            if (is_int($action)) {
                if (empty($val)) {
                    continue;
                }
                list($action, $val) = [$val, $model->{$val} > 0 ? 0 : 1];
            }
            if (empty($action) || !in_array($action, $maps)) {
                continue;
            }
            $model->{$action} = intval($val);
        }
        $model->save();
        return $model;
    }

    public static function remove(array|int $id) {
        $items = ModelHelper::parseArrInt($id);
        if (empty($items)) {
            return;
        }
        FeedbackModel::whereIn('id', $items)->delete();
    }
}