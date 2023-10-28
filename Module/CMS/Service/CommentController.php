<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\Middleware\CMSSeoMiddleware;
use Module\CMS\Domain\Repositories\CommentRepository;
use Zodream\Html\Page;

class CommentController extends Controller {
    public function rules() {
        return [
            'index' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(int $article, int|string $category, int|string $model, string $keywords = '',
                                int $parent_id = 0, string $sort = 'created_at', string $order = 'desc',
                                string $extra = '', int $page = 1, int $pre_page = 10) {
        try {
            $articleId = $article;
            $article = CommentRepository::checkArticle($article, $category, $model);
            $items = CommentRepository::getList($articleId, $category, $model, $keywords, $parent_id, $sort, $order, $extra, $page, $pre_page);
        } catch (\Exception $ex) {
            $items = new Page(0);
            return $this->show(compact('items', 'article'));
        }
        return $this->show(compact('items', 'article'));
    }

    public function createAction(string $content,
                               int $article, int|string $category, int|string $model,
                               int $parent_id = 0) {
        try {
            $articleId = $article;
            $article = CommentRepository::checkArticle($article, $category, $model);
            CommentRepository::create($content,
                $articleId,
                $category,
                $model,
                $parent_id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => CMSSeoMiddleware::encodeUrl(['id' => $article,
                'model_id' => $model,
                'cat_id' => $category])
        ]);
    }

    public function disagreeAction(int $id, int $article,
                                   int|string $category,
                                   int|string $model) {
        try {
            $articleId = $article;
            CommentRepository::checkArticle($article, $category, $model);
            $model = CommentRepository::disagree($id, $articleId, $category, $model);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function agreeAction(int $id, int $article,
                                int|string $category,
                                int|string $model) {
        try {
            $articleId = $article;
            CommentRepository::checkArticle($article, $category, $model);
            $model = CommentRepository::agree($id, $articleId, $category, $model);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function deleteAction(int $id, int $article, int|string $category, int|string $model) {
        try {
            CommentRepository::remove($id, $article, $category, $model);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData(true);
    }
}