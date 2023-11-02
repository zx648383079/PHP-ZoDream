<?php
namespace Module\Forum\Service\Admin;

use Domain\Model\SearchModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;

class ThreadController extends Controller {

    public function indexAction($keywords = null, $forum_id = 0) {
        $items = ThreadModel::with('forum')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'title');
            })->when(!empty($forum_id), function ($query) use ($forum_id) {
                $query->where('forum_id', intval($forum_id));
            })->orderBy('id', 'desc')->page();
        $forum_list = ForumModel::tree()->makeTreeForHtml();
        return $this->show(compact('items', 'forum_list', 'forum_id'));
    }

    public function deleteAction($id) {
        ThreadModel::where('id', $id)->delete();
        ThreadPostModel::where('thread_id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('thread')
        ]);
    }
}