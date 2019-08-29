<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Repositories\TermRepository;
use Module\ModuleController;
use Zodream\Service\Factory;

class HomeController extends ModuleController {

    public $layout = true;

    protected function rules() {
        return [
            'recommend' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(
        $sort = 'new', $category = null, $keywords = null,
        $user = null, $language = null, $programming_language = null,
        $tag = null, $id = 0) {
        if ($id > 0) {
            return $this->runMethodNotProcess('detail', compact('id'));
        }
        $blog_list  = BlogModel::with('user')
            ->select('id', 'title', 'description',
                'created_at', 'comment_count',
                'programming_language',
                'click_count', 'recommend', 'user_id', 'term_id')
            ->when($category > 0, function ($query) use ($category) {
                $query->where('term_id', intval($category));
            })
            ->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', intval($user));
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort == 'recommend') {
                    return $query->orderBy('recommend', 'desc');
                }
                if ($sort == 'hot') {
                    return $query->orderBy('comment_count', 'desc');
                }
            })->when(!empty($keywords), function ($query) {
                BlogModel::search($query, ['title', 'language']);
            })->when(!empty($language), function ($query) use ($language) {
                $query->where('language', $language);
            })->when(!empty($programming_language), function ($query) use ($programming_language) {
                $query->where('programming_language', $programming_language);
            })->when(!empty($tag), function ($query) use ($tag) {
                $query->whereIn('id', TagModel::getBlogByName($tag));
            })
            ->page();
        foreach ($blog_list as $item) {
            $item->term = TermRepository::get($item->term_id);
        }
        $cat_list = TermRepository::get();
        $comment_list = CommentModel::with('blog')
            ->where('approved', 1)->orderBy('created_at', 'desc')->limit(4)->all();
        $new_list = BlogModel::orderBy('created_at', 'desc')
            ->select('id', 'title')
            ->limit(4)->all();
        $term = null;
        if ($category > 0) {
            $term = TermRepository::get($category);
        }
        return $this->show(compact('blog_list',
            'cat_list', 'sort', 'category', 'keywords',
            'comment_list', 'new_list', 'term'));
    }

    public function detailAction($id) {
        $id = intval($id);
        if ($id < 1) {
            return $this->redirect('./');
        }
        $blog = BlogModel::find($id);
        if (empty($blog)) {
            return $this->redirect('./');
        }
        $blog->term = TermRepository::get($blog->term_id);
        $parent_id = $blog->parent_id > 0 ? $blog->parent_id : $blog->id;
        $languages = BlogModel::where('parent_id', $parent_id)->asArray()->get('id', 'language');
        array_unshift($languages, ['id' => $parent_id, 'language' => 'zh']);
        $cat_list = TermRepository::get();
        return $this->show(compact('blog', 'cat_list', 'languages'));
    }

    public function logAction($blog) {
        $this->layout = false;
        $log_list = BlogLogModel::with('user', 'blog')
            ->where('id_value', intval($blog))
            ->where('type', BlogLogModel::TYPE_BLOG)
            ->orderBy('created_at desc')
            ->page();
        return $this->show(compact('log_list'));
    }

    public function counterAction($blog) {
        $id = intval($blog);
        BlogModel::where('id', $id)->updateOne('click_count');
        $blog = BlogModel::query()->where('id', $id)->asArray()
            ->first('click_count', 'recommend', 'comment_count');
        return $this->jsonSuccess($blog);
    }

    public function recommendAction($id) {
        $id = intval($id);
        if (!BlogModel::canRecommend($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $model = BlogModel::find($id);
        $model->recommendThis();
        return $this->jsonSuccess($model->recommend);
    }

    public function suggestAction($keywords) {
        $data = BlogModel::when(!empty($keywords), function ($query) {
           BlogModel::search($query, 'title');
        })->limit(4)->pluck('title', 'id');
        return $this->jsonSuccess($data);
    }

    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}