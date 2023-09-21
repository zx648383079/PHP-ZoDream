<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\CMS\Domain\Model\SiteModel;
use Module\CMS\Domain\ThemeManager;

class SiteRepository {

    const TYPE_CATEGORY = 0;
    const TYPE_ARTICLE = 1;
    const TYPE_COMMENT = 2;
    const TYPE_MODEL = 3;
    const TYPE_MODEL_FIELD = 4;
    const TYPE_LINKAGE = 5;
    const TYPE_LINKAGE_DATA = 5;
    const TYPE_SITE = 6;

    const PUBLISH_STATUS_DRAFT = 0; // 草稿
    const PUBLISH_STATUS_POSTED = 5; // 已发布
    const PUBLISH_STATUS_TRASH = 9; // 垃圾箱

    public static function getList(string $keywords = '') {
        return SiteModel::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['title']);
            })->orderBy('id', 'asc')->page();
    }

    public static function get(int $id) {
        return SiteModel::findOrThrow($id, '数据有误');
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = SiteModel::findOrNew($id);
        $oldTheme = $model->theme;
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        CacheRepository::onSiteUpdated(intval($model->id));
        if (!empty($oldTheme) && $oldTheme !== $model->theme) {
            $old = new SiteModel();
            $old->id = $model->id;
            $old->theme = $oldTheme;
            CMSRepository::removeSite($model);
        }
        if ($oldTheme === $model->theme) {
            return $model;
        }
        try {
            CMSRepository::generateSite($model);
        } catch (\Exception $ex) {
            static::remove($model->id);
            throw $ex;
        }
        return $model;
    }

    public static function remove(int $id) {
        $model = static::get($id);
        $model->delete();
        CMSRepository::removeSite($model);
    }

    public static function setDefault(int $id) {
        $model = static::get($id);
        $model->is_default = 1;
        $model->save();
        SiteModel::where('id', '<>', $id)->update([
            'is_default' => 0
        ]);
    }

    public static function option(int $id) {
        $model = static::get($id);
        return $model->options;
    }

    public static function optionSave(int $id, array $data) {
        $model = static::get($id);
        $model->options = $data;
        $model->save();
    }

    public static function apply(int $id) {
        if (CMSRepository::siteId() === $id) {
            return;
        }
        CMSRepository::site(new SiteModel(compact('id')));
    }

    public static function themeList() {
        return (new ThemeManager)->getAllThemes();
    }

    /**
     * 遍历全部站点
     * @param callable $cb
     * @return void
     * @throws \Exception
     */
    public static function mapTemporary(callable $cb): void {
        $items = SiteModel::query()->get();
        $source = CMSRepository::site();
        $scene = CMSRepository::scene();
        foreach ($items as $item) {
            CMSRepository::site($item);
            call_user_func($cb, $scene, $item);
        }
        CMSRepository::site($source);
    }

    public static function formatStatus(mixed $status): string {
        return match (intval($status)) {
            static::PUBLISH_STATUS_DRAFT => '草稿',
            static::PUBLISH_STATUS_POSTED => '已发布',
            static::PUBLISH_STATUS_TRASH => '已删除',
            default => '--',
        };
    }
}