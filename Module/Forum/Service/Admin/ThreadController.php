<?php
namespace Module\Forum\Service\Admin;

use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;

class ThreadController extends Controller {

    public function indexAction($keywords = null, $forum_id = 0) {
        $thread_list = ThreadModel::with('forum')
            ->when(!empty($keywords), function ($query) {
                $query->where(function ($query) {
                    ThreadModel::search($query, 'title');
                });
            })->when(!empty($forum_id), function ($query) use ($forum_id) {
                $query->where('forum_id', intval($forum_id));
            })->orderBy('id', 'desc')->page();
        $forum_list = ForumModel::tree()->makeTreeForHtml();
        return $this->show(compact('thread_list', 'forum_list', 'forum_id'));
    }
}