<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Blog\Domain\Events\BlogUpdate;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;

final class ManageRepository {
    public static function getList(string $keywords = '', int $category = 0, int $user = 0, int $status = 0, int $type = 0, string $language = '') {
        $items = BlogModel::with('user', 'term')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', $category);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title', 'programming_language']);
            })
            ->where('publish_status', PublishRepository::PUBLISH_STATUS_POSTED)
            ->orderBy('status', 'asc')
            ->orderBy('id', 'desc')->page();
        $items->map(function (BlogModel $item) {
            $data = $item->toArray();
            $data['content'] = BlogRepository::renderContent($item);
            return $data;
        });
        return $items;
    }

    public static function remove(int $id) {
        $model = BlogModel::where('id', $id)
            ->first();
        if (empty($model)) {
            throw new Exception(__('blog is not exist'));
        }
        $model->delete();
        if ($model->parent_id < 1) {
            BlogModel::where('parent_id', $id)->update([
                'parent_id' => 0
            ]);
        }
        BlogMetaModel::deleteBatch($id);
        event(new BlogUpdate($model->id, 2, time()));
    }

    public static function manageChange(int $id, int $status) {
        $model = BlogModel::find($id);
        if (!$model) {
            throw new Exception('id 错误');
        }
        $model->status = $status;
        $model->save();
        return $model;
    }
}