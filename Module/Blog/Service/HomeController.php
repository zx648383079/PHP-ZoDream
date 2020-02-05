<?php
namespace Module\Blog\Service;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Model\TagModel;
use Module\Blog\Domain\Repositories\BlogRepository;
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
        $blog_list  = BlogRepository::getList($sort, $category, $keywords,
            $user, $language, $programming_language, $tag);
        $cat_list = TermRepository::get();
        $comment_list = CommentModel::with('blog')
            ->where('approved', 1)->orderBy('created_at', 'desc')->limit(4)->all();
        $new_list = BlogRepository::getNew(4);
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
        if ($blog->open_type == BlogModel::OPEN_DRAFT &&
            (auth()->guest() || $blog->user_id != auth()->id())) {
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
        $data = BlogSimpleModel::when(!empty($keywords), function ($query) {
           BlogModel::search($query, 'title');
        })->limit(4)->get();
        return $this->jsonSuccess($data);
    }

    public function openAction(int $id) {
        $model = BlogModel::find($id);
        if (!$model) {
            return $this->jsonFailure('文章不存在');
        }
        if ($model->can_read) {
            return $this->jsonSuccess([
                'refresh' => true
            ], '文章可正常阅读');
        }
        if ($model->open_type == BlogModel::OPEN_LOGIN) {
            return $this->jsonSuccess([
                'url' => url('auth', ['redirect_uri' => url()->previous()])
            ], '请先登陆');
        }
        if ($model->open_type == BlogModel::OPEN_PASSWORD) {
            $password = app('request')->get('password');
            if ($password !== $model->open_rule) {
                return $this->jsonFailure('阅读密码错误');
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
            return $this->jsonSuccess([
                'refresh' => true
            ], '密码正确');
        }
        if ($model->open_type == BlogModel::OPEN_BUY) {
            if (auth()->guest()) {
                return $this->jsonSuccess([
                    'url' => url('auth', ['redirect_uri' => url()->previous()])
                ], '请先登陆');
            }
            $res = AccountLogModel::change(
                auth()->id(), AccountLogModel::TYPE_BUY_BLOG,
                $model->id, intval($model->open_rule), '购买文章阅读权限');
            if (!$res) {
                return $this->jsonFailure('账户余额不足');
            }
            BlogLogModel::create([
                'user_id' => auth()->id(),
                'type' => BlogLogModel::TYPE_BLOG,
                'id_value' => $model->id,
                'action' => BlogLogModel::ACTION_REAL_RULE
            ]);
            return $this->jsonSuccess([
                'refresh' => true
            ], '购买成功');
        }
        return $this->jsonFailure('未知');
    }

    public function findLayoutFile() {
        if ($this->layout === false) {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}