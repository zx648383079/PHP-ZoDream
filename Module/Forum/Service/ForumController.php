<?php
declare(strict_types=1);
namespace Module\Forum\Service;

use Module\Forum\Domain\Repositories\ForumRepository;
use Module\Forum\Domain\Repositories\ThreadRepository;

class ForumController extends Controller {

    public function indexAction(int $id, int $classify = 0) {
        try {
            $forum = ForumRepository::getFull($id, true);
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('./', $ex->getMessage());
        }
        $forum_list = $forum->children;
        $thread_list = ThreadRepository::getList($id, $classify);
        $classify_list = $forum->classifies;
        $path = $forum->path;
        return $this->show(compact('forum_list',
            'forum', 'thread_list', 'classify_list', 'path'));
    }
}