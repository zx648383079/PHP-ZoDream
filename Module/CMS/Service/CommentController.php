<?php
declare(strict_types=1);
namespace Module\CMS\Service;

use Module\CMS\Domain\Repositories\CommentRepository;

class CommentController extends Controller {
    public function rules() {
        return [
            'index' => '*',
            '*' => '@',
        ];
    }

    public function indexAction(int $article, int|string $category, int|string $model, string $keywords = '',
                                int $parent_id = 0, string $sort = 'created_at', string $order = 'desc',
                                string $extra = '', int $page = 1, int $pre_page = 20) {
        $items = CommentRepository::getList($article, $category, $model, $keywords, $parent_id, $sort, $order, $extra, $page, $pre_page);
        return $this->show(compact('items'));
    }

    public function createAction(string $content,
                               int $article, int|string $category, int|string $model,
                               int $parent_id = 0) {
        try {
            $model = CommentRepository::create($content,
                $article,
                $category,
                $model,
                $parent_id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => './'
        ]);
    }

    public function disagreeAction(int $id, int $article, int|string $category, int|string $model) {
        try {
            $model = CommentRepository::disagree($id, $article, $category, $model);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function agreeAction(int $id, int $article, int|string $category, int|string $model) {
        try {
            $model = CommentRepository::agree($id, $article, $category, $model);
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