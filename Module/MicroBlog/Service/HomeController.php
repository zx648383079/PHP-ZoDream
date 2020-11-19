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

    public function indexAction($sort = 'new', $keywords = null, $id = 0) {
        $blog_list  = MicroBlogModel::with('user', 'attachment')
            ->when($id > 0, function($query) use ($id) {
                $query->where('id', $id);
            })
            ->when(!empty($sort), function ($query) use ($sort) {
                if ($sort == 'new') {
                    return $query->orderBy('created_at', 'desc');
                }
                if ($sort == 'recommend') {
                    return $query->orderBy('recommend_count', 'desc');
                }
            })->when(!empty($keywords) && $id < 1, function ($query) {
                MicroBlogModel::searchWhere($query, ['content']);
            })
            ->page();
        return $this->show(compact('blog_list', 'keywords'));
    }

    public function createAction(Request $request) {
        if (!MicroRepository::canPublish()) {
            return $this->renderFailure('发送过于频繁！');
        }
        try {
            MicroRepository::create($request->get('content'), $request->get('file'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
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
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function collectAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::collect($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
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
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($model);
    }

    public function deleteAction($id) {
        if (!app('request')->isAjax()) {
            return $this->redirect('./');
        }
        try {
            $model = MicroRepository::delete($id);
        }catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData();
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