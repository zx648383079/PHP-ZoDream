<?php
namespace Module\Forum\Service;


use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;

class ForumController extends Controller {

    public function indexAction($id, $classify = 0) {
        $forum = ForumModel::find($id);
        $forum_list = ForumModel::where('parent_id', $id)->all();
        $thread_list = ThreadModel::with('user', 'classify')
            ->when($classify > 0, function ($query) use ($classify) {
                $query->where('classify_id', intval($classify));
            })->where('forum_id', $id)->orderBy('id', 'desc')->page();
        $classify_list = ForumClassifyModel::where('forum_id', $id)
            ->orderBy('id', 'asc')->all();
        return $this->show(compact('forum_list', 'forum', 'thread_list', 'classify_list'));
    }
}