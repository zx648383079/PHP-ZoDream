<?php
declare(strict_types=1);
namespace Module\MicroBlog\Service\Api;

use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Module\MicroBlog\Domain\Repositories\TopicRepository;

class HomeController extends Controller {
    public function rules() {
        return [
            'recommend' => '@',
            'collect' => '@',
            'forward' => '@',
            '*' => '*'
        ];
    }

    public function indexAction(string $sort = 'new',
                                string $keywords = '',
                                int $id = 0, int $user = 0, int $topic = 0) {
        $items = MicroRepository::getList($sort, $keywords, $id, $user, $topic);
        return $this->renderPage($items);
    }

    public function recommendAction(int $id) {
        try {
            $model = MicroRepository::recommend($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function collectAction(int $id) {
        try {
            $model = MicroRepository::collect($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function detailAction(int $id) {
        $blog = MicroBlogModel::find($id);
        $blog->user;
        $blog->attachment;
        return $this->render($blog);
    }

    public function forwardAction(int $id, string $content, bool $is_comment = false) {
        try {
            $model = MicroRepository::forward($id, $content, $is_comment);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->render($model);
    }

    public function suggestAction(string $keywords = '') {
        return $this->renderPage(TopicRepository::newList($keywords));
    }
}