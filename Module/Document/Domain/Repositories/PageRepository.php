<?php
declare(strict_types=1);
namespace Module\Document\Domain\Repositories;

use Exception;
use Module\Document\Domain\Model\PageModel;
use Zodream\Html\Tree;

class PageRepository {

    public static function tree(int $project, int $version = 0) {
        $data = PageModel::where('project_id', $project)
            ->where('version_id', $version)
            ->select('id', 'name', 'parent_id')
            ->orderBy('id', 'asc')->asArray()->get();
        return (new Tree($data))->makeTree();
    }

    public static function get(int $id) {
        return PageModel::findOrThrow($id, '文档不存在');
    }

    public static function getSelf(int $id) {
        return static::get($id);
    }

    public static function save(array $data) {
        $id = isset($data['id']) ? $data['id'] : 0;
        unset($data['id']);
        $model = $id > 0 ? self::getSelf($id) : new PageModel();
        $model->load($data);
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function removeSelf(int $id) {
        PageModel::where('id', $id)->orWhere('parent_id', $id)->delete();
    }
}