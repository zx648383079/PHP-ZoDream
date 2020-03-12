<?php
namespace Module\Forum\Service;

use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadSimpleModel;
use Module\Forum\Domain\Model\ThreadModel;

class HomeController extends Controller {

    public function indexAction() {
        $forum_list = ForumModel::with('children')
            ->where('parent_id', 0)->all();
        return $this->show(compact('forum_list'));
    }

    public function suggestionAction($keywords = null) {
        $data = ThreadSimpleModel::when(!empty($keywords), function ($query) {
            ThreadModel::searchWhere($query, 'title');
         })->limit(4)->get();
        return $this->jsonSuccess($data);
    }
}