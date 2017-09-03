<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\CommentModel;
use Module\ModuleController;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;

class CommentController extends ModuleController {

    protected function rules() {
        return [
            'index' => '*',
            'save' => '*',
            '*' => '@',
        ];
    }

    public function indexAction($blog_id, $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        $comment_list = CommentModel::where([
            'blog_id' => intval($blog_id),
            'parent_id' => intval($parent_id)
        ])->order($sort, $order)->page();
        return $this->show(compact('comment_list'));
    }

    public function saveAction() {
        $data = Request::request('name,email,url,content,parent_id,blog_id');
        if (!Auth::guest()) {
            $data['user_id'] = Auth::id();
            $data['name'] = Auth::user()->name;
        }
        $model = CommentModel::create($data);
        return $this->jsonSuccess($model);
    }

    public function disagreeAction($id) {
        $id = intval($id);
        $model = CommentModel::find($id);
        $model->disagree ++;
        $model->save();
        return $this->jsonSuccess($model);
    }

    public function agreeAction($id) {
        $id = intval($id);
        $model = CommentModel::find($id);
        $model->agree ++;
        $model->save();
        return $this->jsonSuccess($model);
    }

    public function reportAction($id) {

    }
}