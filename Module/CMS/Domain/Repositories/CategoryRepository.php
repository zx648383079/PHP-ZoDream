<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Exception;
use Module\CMS\Domain\Model\CategoryModel;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Helpers\Arr;
use Zodream\Html\Tree;

class CategoryRepository {
    public static function getList(int $site) {
        SiteRepository::apply($site);
        $modelItems = Arr::pluck(ModelModel::query()->select('id', '`table`')
            ->get(), null, 'id');
        $items = CategoryModel::query()->orderBy('position', 'asc')->get();
        $countKey = 'content_count';
        foreach ($items as &$item) {
            if ($item['type'] > 0 || !isset($modelItems[$item['model_id']])) {
                $item[$countKey] = 0;
                continue;
            }
            $item[$countKey] = CMSRepository::scene()->setModel($modelItems[$item['model_id']], $site)
                ->query()->where('model_id', $item['model_id'])
                ->where('cat_id', $item['id'])->count();
        }
        unset($item);
        return (new Tree($items))
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
        if (is_numeric($data['name'])) {
            $data['name'] = sprintf('%s%d',
                CMSRepository::generateTableName($data['title']),
                $data['name']);
        }
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        CacheRepository::onChannelUpdated(intval($model->id));
        return $model;
    }

    public static function remove(int $site, int $id) {
        SiteRepository::apply($site);
        CategoryModel::where('id', $id)->delete();
        CacheRepository::onChannelUpdated($id);
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