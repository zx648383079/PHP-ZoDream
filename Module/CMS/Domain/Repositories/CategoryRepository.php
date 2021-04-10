<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Exception;
use Module\CMS\Domain\Model\CategoryModel;
use Zodream\Html\Tree;

class CategoryRepository {
    public static function getList(int $site) {
        SiteRepository::apply($site);
        return (new Tree(CategoryModel::query()->orderBy('position', 'asc')->get()))
            ->makeTreeForHtml();
    }

    public static function get(int $site, int $id) {
        SiteRepository::apply($site);
        return CategoryModel::findOrThrow($id, '数据有误');
    }

    public static function save(int $site, array $data) {
        SiteRepository::apply($site);
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = CategoryModel::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $site, int $id) {
        SiteRepository::apply($site);
        CategoryModel::where('id', $id)->delete();
    }

    public static function all(int $site) {
        SiteRepository::apply($site);
        return (new Tree(CategoryModel::query()->orderBy('position', 'asc')
            ->get('id', 'title', 'parent_id')))
            ->makeTreeForHtml();
    }

    public static function apply(int $site, int $id) {
        SiteRepository::apply($site);
        $modelId = CategoryModel::where('id', $id)
            ->value('model_id');
        if ($modelId < 1) {
            throw new Exception('栏目不包含模型');
        }
        return CMSRepository::scene()->setModel(ModelRepository::get(intval($modelId)), $site);
    }

}