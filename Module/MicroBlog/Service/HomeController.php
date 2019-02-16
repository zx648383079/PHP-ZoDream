<?php
namespace Module\MicroBlog\Service;

use Module\MicroBlog\Domain\Model\MicroBlogModel;
use Module\ModuleController;
use Zodream\Service\Factory;

class HomeController extends ModuleController {

    protected function rules() {
        return [
            'recommend' => '@',
            'create' => '@',
            '*' => '*'
        ];
    }

    public function indexAction($sort = 'new', $keywords = null) {
        $blog_list  = MicroBlogModel::with('user')
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

    public function createAction() {
        if (!MicroBlogModel::canPublish()) {
            return $this->jsonFailure('发送过于频繁！');
        }
        $model = new MicroBlogModel();
        $model->user_id = auth()->id();
        $model->content = app('request')->get('content');
        if ($model->save()) {
            return $this->jsonSuccess([
                'url' => url('./')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function recommendAction($id) {
        $id = intval($id);
        if (!MicroBlogModel::canRecommend($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $model = MicroBlogModel::find($id);
        $model->recommendThis();
        return $this->jsonSuccess($model->recommend);
    }

    public function findLayoutFile() {
        if ($this->action !== 'index') {
            return false;
        }
        return Factory::root()->file('UserInterface/Home/layouts/main.php');
    }
}