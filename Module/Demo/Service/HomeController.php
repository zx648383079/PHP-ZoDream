<?php
namespace Module\Demo\Service;


use Module\Demo\Domain\Model\CategoryModel;
use Module\Demo\Domain\Model\PostModel;
use Module\Demo\Domain\Model\TagModel;

class HomeController extends Controller {

    public function indexAction(
        $sort = 'new', $category = null, $keywords = null,
        $user = null,
        $tag = null, $id = 0) {
        if ($id > 0) {
            return $this->runMethodNotProcess('detail', compact('id'));
        }
        $blog_list  = PostModel::with('user', 'category')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('cat_id', intval($category));
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', intval($user));
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if (is_array($sort)) {
                    // 增加直接放id
                    return $query->whereIn('id', $sort)->orderBy('created_at', 'desc');
                }
                if ($sort === 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort === 'hot') {
                    return $query->orderBy('download_count', 'desc');
                }
            })->when(!empty($keywords), function ($query) {
                PostModel::searchWhere($query, ['title']);
            })->when(!empty($tag), function ($query) use ($tag) {
                $ids = TagModel::getPostByName($tag);
                if (empty($ids)) {
                    $query->isEmpty();
                    return;
                }
                $query->whereIn('id', $ids);
            })->page();
        $cat_list = CategoryModel::query()->all();
        $cat = null;
        if ($category > 0) {
            $cat = CategoryModel::find($category);
        }
        return $this->show(compact('blog_list',
            'cat_list', 'sort', 'category', 'keywords',
            'cat', 'tag'));
    }

    public function detailAction($id) {
        $id = intval($id);
        if ($id < 1) {
            return $this->redirect('./');
        }
        $post = PostModel::find($id);
        return $this->show(compact('post'));
    }
}