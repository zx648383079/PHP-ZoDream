<?php
namespace Module\Forum\Service;


use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;

class ForumController extends Controller {

    public function indexAction($id) {
        $forum = ForumModel::find($id);
        $forum_list = ForumModel::where('parent_id', $id)->all();
        $thread_list = ThreadModel::where('forum_id', $id)->page();
        return $this->show(compact('forum_list', 'forum', 'thread_list'));
    }
}