<?php
declare(strict_types=1);
namespace Module\Blog\Service;

use Domain\Model\SearchModel;
use Domain\Repositories\LocalizeRepository;
use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Blog\Domain\Model\BlogLogModel;
use Module\Blog\Domain\Model\BlogMetaModel;
use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\BlogSimpleModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\Blog\Domain\Repositories\BlogRepository;
use Module\Blog\Domain\Repositories\CategoryRepository;
use Module\Blog\Domain\Repositories\CommentRepository;
use Module\Blog\Domain\Repositories\PublishRepository;
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
        int $user = 0, string $programming_language = '',
        string $tag = '', int $id = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        $blog_list  = BlogRepository::getList($sort, $category, $keywords,
            $user, LocalizeRepository::browserLanguage(), $programming_language, $tag);
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

    public function detailAction(int $id, string $password = '') {
        try {
            if (empty($password)) {
                $password = session('BLOG_PWD');
            }
            list($blog, $readRole) = BlogRepository::getWithRole($id, (string)$password);
        } catch (\Exception) {
            return $this->redirect('./');
        }
        if ($readRole < 1) {
            return $this->redirect('./');
        }
        $blog->can_read = $readRole > 1;
        $blog->term = CategoryRepository::get($blog->term_id);
        $parent_id = $blog->parent_id > 0 ? $blog->parent_id : $blog->id;
        $languages = BlogModel::where('parent_id', $parent_id)->asArray()->get('id', 'language');
        array_unshift($languages, ['id' => $parent_id, 'language' => 'zh']);
        $cat_list = CategoryRepository::get();
        $tags = TagRepository::getTags($blog->id);
        $relation_list = TagRepository::getRelationBlogs($blog->id);
        $metaItems = BlogMetaModel::getOrDefault($id);
        $metaItems['comment_status'] = CommentRepository::commentStatus($metaItems['comment_status']);
        return $this->show('detail', compact('blog', 'cat_list', 'languages', 'tags', 'relation_list', 'metaItems'));
    }

    public function logAction(int $blog) {
        $this->layout = '';
        $log_list = BlogRepository::getLogList($blog);
        return $this->show(compact('log_list'));
    }

    public function counterAction(int $blog) {
        BlogRepository::addClick($blog);
        $model = BlogRepository::getStatistics($blog);
        return $this->renderData($model);
    }

    public function recommendAction(int $id) {
        try {
            $model = BlogRepository::recommend($id);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model->recommend_count);
    }

    public function suggestionAction(string $keywords) {
        $data = BlogSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'title');
        })->limit(4)->get();
        return $this->renderData($data);
    }

    public function openAction(int $id) {
        try {
            $key = request('password');
            BlogRepository::detailOpen($id, $key);
            if (!empty($key)) {
                session(['BLOG_PWD' => $key]);
            }
            return $this->renderData([
                'refresh' => true
            ]);
        } catch (\Exception $ex) {
            if ($ex->getCode() === 401) {
                return $this->renderData([
                    'url' => url('auth', ['redirect_uri' => url()->previous()])
                ], $ex->getMessage());
            }
            return $this->renderFailure($ex->getMessage());
        }
    }

}