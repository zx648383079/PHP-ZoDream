<?php
namespace Module\Forum\Service;


use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadModel;

class ForumController extends Controller {

    public function indexAction($id, $classify = 0) {
        $forum = ForumModel::findById($id);
        if (empty($forum)) {
            return $this->redirectWithMessage('./');
        }
        $forum_list = ForumModel::findChildren($id);
        $thread_list = ThreadModel::with('user', 'classify')
            ->when($classify > 0, function ($query) use ($classify) {
                $query->where('classify_id', intval($classify));
            })->whereIn('forum_id', ForumModel::getAllChildrenId($id))
            ->orderBy('id', 'desc')->page();
        $classify_list = ForumClassifyModel::where('forum_id', $id)
            ->orderBy('id', 'asc')->all();
        $path = ForumModel::findPath($id);
        return $this->show(compact('forum_list',
            'forum', 'thread_list', 'classify_list', 'path'));
    }
}