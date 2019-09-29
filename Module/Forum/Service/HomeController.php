<?php
namespace Module\Forum\Service;


use Module\Forum\Domain\Model\ForumModel;

class HomeController extends Controller {

    public function indexAction() {
        $forum_list = ForumModel::with('children')
            ->where('parent_id', 0)->all();
        return $this->show(compact('forum_list'));
    }
}