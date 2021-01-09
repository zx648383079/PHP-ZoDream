<?php
namespace Module\Forum\Service\Api;

use Module\Forum\Domain\Model\ForumClassifyModel;
use Module\Forum\Domain\Model\ForumModel;
use Module\Forum\Domain\Model\ThreadSimpleModel;
use Module\Forum\Domain\Model\ThreadModel;

class HomeController extends Controller {

    public function indexAction($parent_id = 0) {
        $forum_list = ForumModel::with('children')
            ->where('parent_id', $parent_id)->all();
        return $this->renderData($forum_list);
    }

    public function detailAction($id) {
        $forum = ForumModel::find($id);
        $forum->classifies;
        $forum->moderators;
        $forum->path = ForumModel::findPath($id);
        return $this->render($forum);
    }

    public function suggestionAction($keywords = null) {
        $data = ThreadSimpleModel::when(!empty($keywords), function ($query) {
            ThreadModel::searchWhere($query, 'title');
         })->limit(4)->get();
        return $this->renderData($data);
    }
}