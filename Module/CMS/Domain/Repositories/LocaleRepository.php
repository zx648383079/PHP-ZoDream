<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Module\CMS\Domain\Entities\CategoryEntity;
use Module\CMS\Domain\Entities\LinkageDataEntity;
use Module\CMS\Domain\Entities\LinkageEntity;
use Module\CMS\Domain\Entities\SiteEntity;
use Module\CMS\Domain\Model\ModelModel;

final class LocaleRepository {


    public static function linkageOptions(LinkageEntity $model): array {
        if (!$model->language) {
            return [];
        }
        $items = LinkageEntity::where('code', $model->code)
            ->where('type', $model->type)
            ->asArray()
            ->get('id', 'language', 'name');
        if ($items < 2) {
            return [];
        }
        return array_map(function ($item) use($model) {
            return [
                'selected' => intval($model->id) === intval($item['id']) ,
                'language' => $item['language'],
                'id' => intval($item['id']),
                'name' => $item['name']
            ];
        }, $items);
    }

    public static function linkageConvert(int $linkage, int $id = 0): int {
        if ($id <= 0) {
            return 0;
        }
        $source = LinkageDataEntity::where('id', $id)->first('locale_group_id', 'linkage_id');
        if (intval($source->linkage_id) === $linkage) {
            return $id;
        }
        if (!$source->locale_group_id) {
            return 0;
        }
        return intval(LinkageDataEntity::where('locale_group_id', $source->locale_group_id)->where('linkage_id', $linkage)
            ->value('id'));
    }

    public static function siteOptions(SiteEntity|null $model = null): array {
        if (empty($model)) {
            $model = CMSRepository::context()->source();
        }
        $locale_group_id = intval($model->locale_group_id);
        if ($locale_group_id === 0) {
            return [];
        }
        $items = SiteEntity::where('locale_group_id', $locale_group_id)
            ->asArray()
            ->get('id', 'language', 'title');
        if ($items < 2) {
            return [];
        }
        return array_map(function ($item) use($model) {
            return [
                'selected' => intval($model->id) === intval($item['id']) ,
                'language' => $item['language'],
                'id' => intval($item['id']),
                'name' => $item['title']
            ];
        }, $items);
    }

    public static function channelConvert(int $site, int $id = 0): int {
        if ($id <= 0) {
            return 0;
        }
        $context = CMSRepository::context();
        $source = $context->channelBuilder()->where('id', $id)->first('locale_group_id', 'site_id');
        if (intval($source->site_id) === $site) {
            return $id;
        }
        if (!$source->locale_group_id) {
            return 0;
        }
        return intval($context->channelBuilder()->where('locale_group_id', $source->locale_group_id)->where('site_id', $site)
            ->value('id'));
    }

    public static function articleConvert(int $site, ModelModel $model, int $id = 0): int {
        if ($id <= 0) {
            return 0;
        }
        $scene = CMSRepository::context()->scene()->setModel($model);
        $source = $scene->query()->where('id', $id)->first('locale_group_id', 'site_id');
        if (intval($source->site_id) === $site) {
            return $id;
        }
        if (!$source->locale_group_id) {
            return 0;
        }
        return intval($scene->query()->where('locale_group_id', $source->locale_group_id)->where('site_id', $site)
            ->value('id'));
    }

    public static function siteBinding(SiteEntity $model): bool {
        if (!$model->language) {
            return false;
        }
        $items = SiteEntity::where('theme', $model->theme)
            ->where('id', '<>', $model->id)
            ->whereNotNull('language')->orderBy('id', 'asc')
            ->get('id', 'language', 'locale_group_id');
        if (count($items) < 1) {
            return false;
        }
        $locale_group_id = intval($items[0]['locale_group_id']);
        if ($locale_group_id === 0) {
            $locale_group_id = intval($items[0]['id']);
        }
        $model->locale_group_id = $locale_group_id;
        $idItems = array_map(function ($item) {
            return intval($item['id']);
        }, $items);
        $idItems[] = $model->id;
        SiteEntity::query()->whereIn('id', $idItems)->update(compact('locale_group_id'));
        return true;
    }
}