<?php
namespace Module\Forum\Service\Admin;

use Module\Forum\Domain\Model\ForumModel;

class ForumController extends Controller {

    public function indexAction() {
        $forum_list = ForumModel::tree()->makeTreeForHtml();
        return $this->show(compact('forum_list'));
    }

    public function createAction() {
        return $this->runMethodNotProcess('edit', ['id' => null]);
    }

    public function editAction($id) {
        $model = ForumModel::findOrDefault($id, [
            'position' => 9
        ]);
        $forum_list = ForumModel::tree()->makeTreeForHtml();
        if (!empty($id)) {
            $excludes = [$id];
            $forum_list = array_filter($forum_list, function ($item) use (&$excludes) {
                if (in_array($item['id'], $excludes)) {
                    return false;
                }
                if (in_array($item['parent_id'], $excludes)) {
                    $excludes[] = $item['id'];
                    return false;
                }
                return true;
            });
        }
        return $this->show(compact('model', 'forum_list'));
    }

    public function saveAction() {
        $model = new ForumModel();
        if ($model->load() && $model->autoIsNew()->save()) {
            return $this->jsonSuccess([
                'url' => $this->getUrl('forum')
            ]);
        }
        return $this->jsonFailure($model->getFirstError());
    }

    public function deleteAction($id) {
        ForumModel::where('id', $id)->delete();
        return $this->jsonSuccess([
            'url' => $this->getUrl('forum')
        ]);
    }
}