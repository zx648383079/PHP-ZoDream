<?php
namespace Module\Forum\Service;


use Module\Forum\Domain\Model\ThreadModel;
use Module\Forum\Domain\Model\ThreadPostModel;

class ThreadController extends Controller {

    public function indexAction($id) {
        $thread = ThreadModel::find($id);
        $post_list = ThreadPostModel::where('thread_id', $id)
            ->orderBy('is_first', 'desc')
            ->orderBy('created_at', 'asc')->page();
        return $this->show(compact('thread', 'post_list'));
    }
}