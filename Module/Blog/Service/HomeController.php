<?php
declare(strict_types=1);
namespace Module\Blog\Service;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\CategoryRepository;
use Module\Blog\Domain\Repositories\CommentRepository;
use Module\Blog\Domain\Repositories\TagRepository;

class HomeController extends Controller {

    public function rules() {
        return [
            'recommend' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(
        string $sort = 'new', int $category = 0, string $keywords = '',
        int $user = 0, string $language = '', string $programming_language = '',
        string $tag = '', int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $blog_list  = BlogRepository::getList($sort, $category, $keywords,
            $user, $language, $programming_language, $tag);
        $cat_list = CategoryRepository::get();
        $comment_list = CommentRepository::newList();
        $new_list = BlogRepository::getNew(4);
        $term = null;
        if ($category > 0) {
            $term = CategoryRepository::get($category);
        }
        return $this->show(compact('blog_list',
            'cat_list', 'sort', 'category', 'keywords',
            'comment_list', 'new_list', 'term', 'tag', 'programming_language'));
    }

    public function detailAction(int $id) {
        if ($id < 1) {
            return $this->redirect('./');
        }
        $blog = BlogModel::find($id);
        if (empty($blog)) {
            return $this->redirect('./');
        }
        if ($blog->open_type == BlogModel::OPEN_DRAFT &&
            (auth()->guest() || $blog->user_id != auth()->id())) {
            return $this->redirect('./');
        }
        $blog->term = CategoryRepository::get($blog->term_id);
        $parent_id = $blog->parent_id > 0 ? $blog->parent_id : $blog->id;
        $languages = BlogModel::where('parent_id', $parent_id)->asArray()->get('id', 'language');
        array_unshift($languages, ['id' => $parent_id, 'language' => 'zh']);
        $cat_list = CategoryRepository::get();
        $tags = TagRepository::getTags($blog->id);
        $relation_list = TagRepository::getRelationBlogs($blog->id);
        $metaItems = BlogMetaModel::getOrDefault($id);
        return $this->show('detail', compact('blog', 'cat_list', 'languages', 'tags', 'relation_list', 'metaItems'));
    }

    public function logAction(int $blog) {
        $this->layout = false;
        $log_list = BlogLogModel::with('user', 'blog')
            ->where('id_value', $blog)
            ->where('type', BlogLogModel::TYPE_BLOG)
            ->orderBy('created_at desc')
            ->page();
        return $this->show(compact('log_list'));
    }

    public function counterAction(int $blog) {
        $id = $blog;
        BlogModel::where('id', $id)->updateIncrement('click_count');
        $blog = BlogModel::query()->where('id', $id)->asArray()
            ->first('click_count', 'recommend_count', 'comment_count');
        return $this->renderData($blog);
    }

    public function recommendAction(int $id) {
        if (!BlogModel::canRecommend($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $model = BlogModel::find($id);
        $model->recommendThis();
        return $this->renderData($model->recommend_count);
    }

    public function suggestionAction(string $keywords) {
        $data = BlogSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'title');
        })->limit(4)->get();
        return $this->renderData($data);
    }

    public function openAction(int $id) {
        $model = BlogModel::find($id);
        if (!$model) {
            return $this->renderFailure('文章不存在');
        }
        if ($model->can_read) {
            return $this->renderData([
                'refresh' => true
            ], '文章可正常阅读');
        }
        if ($model->open_type == BlogModel::OPEN_LOGIN) {
            return $this->renderData([
                'url' => url('auth', ['redirect_uri' => url()->previous()])
            ], '请先登陆');
        }
        if ($model->open_type == BlogModel::OPEN_PASSWORD) {
            $password = request()->get('password');
            if ($password !== $model->open_rule) {
                return $this->renderFailure('阅读密码错误');
            }
            session(['BLOG_PWD' => $password]);
            if (!auth()->guest()) {
                BlogLogModel::create([
                    'user_id' => auth()->id(),
                    'type' => BlogLogModel::TYPE_BLOG,
                    'id_value' => $model->id,
                    'action' => BlogLogModel::ACTION_REAL_RULE
                ]);
            }
            return $this->renderData([
                'refresh' => true
            ], '密码正确');
        }
        if ($model->open_type == BlogModel::OPEN_BUY) {
            if (auth()->guest()) {
                return $this->renderData([
                    'url' => url('auth', ['redirect_uri' => url()->previous()])
                ], '请先登陆');
            }
            $res = AccountLogModel::change(
                auth()->id(), AccountLogModel::TYPE_BUY_BLOG,
                $model->id, intval($model->open_rule), '购买文章阅读权限');
            if (!$res) {
                return $this->renderFailure('账户余额不足');
            }
            BlogLogModel::create([
                'user_id' => auth()->id(),
                'type' => BlogLogModel::TYPE_BLOG,
                'id_value' => $model->id,
                'action' => BlogLogModel::ACTION_REAL_RULE
            ]);
            return $this->renderData([
                'refresh' => true
            ], '购买成功');
        }
        return $this->renderFailure('未知');
    }

}