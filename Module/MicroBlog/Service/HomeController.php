<?php
namespace Module\MicroBlog\Service;

use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\MicroBlog\Domain\Repositories\MicroRepository;
use Module\ModuleController;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Config;
use Zodream\Service\Factory;

class HomeController extends ModuleController {

    protected function rules() {
        return [
            'recommend' => '@',
            'create' => '@',
            'forward' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($sort = 'new', $keywords = null) {
        $blog_list  = MicroBlogModel::with('user', 'attachment')
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort == 'recommend') {
                    return $query->orderBy('recommend', 'desc');
                }
            })->when(!empty($keywords), function ($query) {
                MicroBlogModel::search($query, ['content']);
            })
            ->page();
        return $this->show(compact('blog_list', 'keywords'));
    }

    public function createAction(Request $request) {
        if (!MicroRepository::canPublish()) {
            return $this->jsonFailure('发送过于频繁！');
        }
        $model = MicroRepository::create($request->get('content'), $request->get('file'));
        if (!$model) {
            return $this->jsonFailure('发送失败');
        }
        return $this->jsonSuccess([
            'url' => url('./')
        ]);

    }

    public function recommendAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::recommend($id);
        }catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($model);
    }

    public function forwardMiniAction($id) {
        $this->layout = false;
        $blog = MicroBlogModel::find($id);
        return $this->show(compact('blog'));
    }

    public function forwardAction($id, $content, $is_comment = false) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::forward($id, $content, $is_comment);
        }catch (\Exception $ex) {
            return $this->jsonFailure($ex->getMessage());
        }
        return $this->jsonSuccess($model);
    }

    public function findLayoutFile() {
        if ($this->action !== 'index') {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }

    public function redirectWithAuth() {
        return $this->redirect([Config::auth('home'), 'redirect_uri' => url('./')]);
    }
}