<?php
namespace Module\Blog\Service\Api;


use Module\Blog\Domain\Model\MicroBlogModel;
use Zodream\Route\Controller\RestController;

class MicroController extends RestController {

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
        return $this->renderPage($blog_list);
    }

    public function createAction() {
        if (!MicroBlogModel::canPublish()) {
            return $this->renderFailure('发送过于频繁！');
        }
        $model = new MicroBlogModel();
        $model->user_id = auth()->id();
        $model->content = app('request')->get('content');
        if (!$model->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        return $this->render($model->toArray());
    }

    public function recommendAction($id) {
        $id = intval($id);
        if (!MicroBlogModel::canRecommend($id)) {
            return $this->renderFailure('一个用户只能操作一次！');
        }
        $model = MicroBlogModel::find($id);
        $model->recommendThis();
        return $this->render($model->toArray());
    }
}