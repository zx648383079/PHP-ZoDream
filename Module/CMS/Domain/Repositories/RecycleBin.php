<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;


use Domain\Model\SearchModel;
use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\Entities\GroupEntity;
use Module\CMS\Domain\Entities\LinkageDataEntity;
use Module\CMS\Domain\Entities\LinkageEntity;
use Module\CMS\Domain\Entities\RecycleBinEntity;
use Module\CMS\Domain\Model\LinkageDataModel;
use Module\CMS\Domain\Model\LinkageModel;
use Zodream\Database\Model\Model;
use Zodream\Helpers\Json;

final class RecycleBin {

    public static function getList(string $keywords = '', int $site = 0) {
        return RecycleBinEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['title']);
        })->when($site > 0, function ($query) use ($site) {
            $query->where('site_id', $site);
        })->select('id', 'title', 'site_id', 'created_at')->page();
    }

    public static function remove(int|array $id): void {
        RecycleBinEntity::whereIn('id', (array)$id)->delete();
    }

    public static function clear(): void {
        RecycleBinEntity::query()->delete();
    }

    public static function restore(int|array $id): void {
        $items = RecycleBinEntity::whereIn('id', (array)$id)->get();
        $success = [];
        foreach ($items as $item) {
            if ($item['site_id'] > 0) {
                SiteRepository::apply(intval($item['site_id']));
            }
            $data = Json::decode($item['data']);
            switch (intval($item['item_type'])) {
                case SiteRepository::TYPE_CATEGORY:
                    CategoryEntity::query()->insert($data);
                    break;
                case SiteRepository::TYPE_GROUP:
                    GroupEntity::query()->insert($data);
                    break;
                case SiteRepository::TYPE_LINKAGE:
                    $items = $data['items'];
                    unset($data['items']);
                    LinkageEntity::query()->insert($data);
                    LinkageDataEntity::query()->insert($items);
                    break;
                case SiteRepository::TYPE_ARTICLE:
                    $scene = ContentRepository::apply(intval($item['site_id']), intval($data['cat_id']),
                        intval($item['model_id']));
                    $scene->insert($data);
                    break;
                default:
                    break;
            }
            $success[] = $item['id'];
        }
        RecycleBinEntity::whereIn('id', $success)->delete();
    }

    public static function add(int $site, int $type, string $title, array $data, int $model = 0): void {
        RecycleBinEntity::query()->insert([
            'site_id' => $site,
            'model_id' => $model,
            'title' => $title,
            'item_type' => $type,
            'item_id' => $data['id'],
            'user_id' => auth()->id(),
            'data' => Json::encode($data),
            Model::CREATED_AT => time()
        ]);
    }

    public static function addCategory(int $site, array|int $data): void {
        if (is_numeric($data)) {
            $id = intval($data);
            $data = CategoryEntity::query()->where('id',$id)->asArray()->first();
            if (empty($data)) {
                return;
            }
        }
        self::add($site, SiteRepository::TYPE_CATEGORY, $data['title'], $data);
    }

    public static function addContent(int $site, array|int $data): void {
        if (is_numeric($data)) {
            $id = intval($data);
            $data = CMSRepository::scene()->find($id);
            if (empty($data)) {
                return;
            }
        }
        self::add($site, SiteRepository::TYPE_ARTICLE, $data['title'], $data, $data['model_id']);
    }

    public static function addGroup(array|int $data): void {
        if (is_numeric($data)) {
            $id = intval($data);
            $data = GroupEntity::query()->where('id',$id)->asArray()->first();
            if (empty($data)) {
                return;
            }
        }
        self::add(0, SiteRepository::TYPE_GROUP, $data['name'], $data);
    }

    public static function addLinkage(array|int $data): void {
        if (is_numeric($data)) {
            $id = intval($data);
            $data = LinkageEntity::query()->where('id',$id)->asArray()->first();
            if (empty($data)) {
                return;
            }
            $data['items'] = LinkageDataEntity::query()->where('linkage_id', $id)
                ->orderBy('position', 'asc')
                ->orderBy('id', 'asc')->asArray()->get();
        }
        self::add(0, SiteRepository::TYPE_LINKAGE, $data['name'], $data);
    }

}