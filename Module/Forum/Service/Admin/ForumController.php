<?php
namespace Module\Forum\Service\Admin;

use Module\Forum\Domain\Model\ForumClassifyModel;
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
        $classify_list = [];
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
            $classify_list = ForumClassifyModel::where('forum_id', $id)
                ->orderBy('id', 'asc')->all();
        }
        return $this->show(compact('model', 'forum_list', 'classify_list'));
    }

    public function saveAction() {
        $model = new ForumModel();
        if (!$model->load() || !$model->autoIsNew()->save()) {
            return $this->renderFailure($model->getFirstError());
        }
        $this->saveClassify($model->id);
        return $this->renderData([
            'url' => $this->getUrl('forum')
        ]);

    }

    public function deleteAction($id) {
        ForumModel::where('id', $id)->delete();
        return $this->renderData([
            'url' => $this->getUrl('forum')
        ]);
    }

    protected function saveClassify($forum_id) {
        $data = $this->getClassify(app('request')->get('classify'),
            'name', 'id', 'icon', 'position');
        $exits = ForumClassifyModel::where('forum_id', $forum_id)->pluck('id');
        if (!empty($exits)) {
            $exclude = array_diff($exits, array_column($data, 'id'));
            if (!empty($exclude)) {
                ForumClassifyModel::whereIn('id', $exclude)->delete();
            }
        }
        foreach ($data as $item) {
            $id = $item['id'];
            $item['position'] = intval($item['position']);
            unset($item['id']);
            $item['forum_id'] = $forum_id;
            if ($id > 0) {
                ForumClassifyModel::where('id', $id)->update($item);
                continue;
            }
            ForumClassifyModel::query()->insert($item);
        }
    }

    protected function getClassify(array $data, $key, ...$fields) {
        if (empty($data)) {
            return [];
        }
        if (!isset($data[$key]) || !is_array($data[$key])) {
            return [];
        }
        $args = [];
        $key_list = [];
        foreach ($data[$key] as $i => $value) {
            $value = trim($value);
            if (empty($value) || in_array($value, $key_list)) {
                // 保证唯一不为空
                continue;
            }
            $key_list[] = $value;
            $item = [
                $key => $value
            ];
            foreach ($fields as $field) {
                $item[$field] = isset($data[$field][$i]) ? trim($data[$field][$i]) : null;
            }
            $args[] = $item;
        }
        return $args;
    }
}