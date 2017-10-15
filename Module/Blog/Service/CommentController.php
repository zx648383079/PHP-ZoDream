<?php
namespace Module\Blog\Service;

use Module\Blog\Domain\Model\BlogModel;
use Module\Blog\Domain\Model\CommentModel;
use Module\ModuleController;
use Zodream\Domain\Access\Auth;
use Zodream\Infrastructure\Http\Request;

class CommentController extends ModuleController {

    protected function rules() {
        return [
            'index' => '*',
            'save' => '*',
            'more' => '*',
            '*' => '@',
        ];
    }

    public function indexAction($blog_id) {
        $hot_comments = CommentModel::where([
            'blog_id' => intval($blog_id),
            'parent_id' => 0,
        ])->where('agree', '>', 0)->order('agree desc')->limit(4)->all();
        return $this->show(compact('hot_comments', 'blog_id'));
    }

    public function moreAction($blog_id, $parent_id = 0, $sort = 'created_at', $order = 'desc') {
        $comment_list = CommentModel::where([
            'blog_id' => intval($blog_id),
            'parent_id' => intval($parent_id)
        ])->order($sort, $order)->page();
        return $this->show(compact('comment_list'));
    }

    public function saveAction() {
        $data = Request::request('name,email,url,content,parent_id,blog_id');
        if (!BlogModel::canComment($data['blog_id'])) {
            return $this->jsonFailure('不允许评论！');
        }
        if (!Auth::guest()) {
            $data['user_id'] = Auth::id();
            $data['name'] = Auth::user()->name;
        }
        $model = CommentModel::create($data);
        return $this->jsonSuccess($model);
    }

    public function disagreeAction($id) {
        $id = intval($id);
        if (!CommentModel::canAgree($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $model = CommentModel::find($id);
        $model->disagree ++;
        $model->save();
        return $this->jsonSuccess($model->disagree);
    }

    public function agreeAction($id) {
        $id = intval($id);
        if (!CommentModel::canAgree($id)) {
            return $this->jsonFailure('一个用户只能操作一次！');
        }
        $model = CommentModel::find($id);
        $model->agree ++;
        $model->save();
        return $this->jsonSuccess($model->agree);
    }

    public function reportAction($id) {

    }

    public function logAction() {
        CommentModel::alias('c');
    }
}