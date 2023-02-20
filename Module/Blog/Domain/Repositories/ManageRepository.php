<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;


use Domain\Model\SearchModel;
use Exception;
use Module\Blog\Domain\Events\BlogUpdate;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogPageModel;

final class ManageRepository {
    public static function getList(string $keywords = '', int $category = 0, int $user = 0) {
        return BlogPageModel::with('user', 'term')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', $category);
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title', 'programming_language']);
            })->page();
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
}