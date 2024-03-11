<?php
declare(strict_types=1);
namespace Module\AdSense\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\AdSense\Domain\Entities\AdEntity;
use Module\AdSense\Domain\Entities\AdPositionEntity;
use Module\AdSense\Domain\Models\AdModel;
use Module\AdSense\Domain\TemplateCompiler;

final class AdRepository {

    const TYPE_TEXT = 0;
    const TYPE_IMAGE = 1;
    const TYPE_HTML = 2;
    const TYPE_VIDEO = 3;


    public static function banners(): array {
        return self::load('banner');
    }

    public static function mobileBanners(): array {
        return self::load('mobile_banner');
    }

    public static function getList(string $keywords = '', int|string $position = 0) {
        $now = time();
        return AdModel::query()
            ->with('position')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->when(!empty($position), function ($query) use ($position) {
                if (is_numeric($position)) {
                    $query->where('position_id', $position);
                    return;
                }
                $positionId = AdPositionEntity::where('name', $position)
                    ->value('id');
                if (empty($positionId)) {
                    $query->isEmpty();
                    return;
                }
                $query->where('position_id', $positionId);
            })->where('start_at', '<=', $now)
            ->where('end_at', '>', $now)->get();
    }

    public static function get(int $id) {
        $now = time();
        $model = AdEntity::query()
            ->with('position')->where('id', $id)
            ->where('start_at', '<=', $now)
            ->where('end_at', '>', $now)->first();
        if (empty($model)) {
            throw new Exception('广告不存在');
        }
        return $model;
    }

    public static function load(string $code): array {
        $position = AdPositionEntity::where('code', $code)
            ->where('status', 1)
            ->first();
        if (empty($position)) {
            return [];
        }
        if ($position['source_type'] > 0) {
            return $position['template'];
        }
        $items = AdEntity::query()->where('position_id', $position['id'])
            ->where('status', 1)->get();
        return self::format($position, $items, false);
    }

    public static function render(string $code): string {
        $position = AdPositionEntity::where('code', $code)
            ->where('status', 1)
            ->first();
        if (empty($position)) {
            return '';
        }
        if ($position['source_type'] > 0) {
            return $position['template'];
        }
        $items = AdEntity::query()->where('position_id', $position['id'])
            ->where('status', 1)->get();
        $items = self::format($position, $items);
        return TemplateCompiler::render($position['template'], $items);
    }

    private static function formatSize(array|AdPositionEntity $position): array {
        if ($position['auto_size'] > 0) {
            return [
                'width' => '100%'
            ];
        }
        $data = [];
        if ($position['width']) {
            $data['width'] = is_numeric($position['width']) ? $position['width'].'px' : $position['width'];
        }
        if ($position['height']) {
            $data['height'] = is_numeric($position['height']) ? $position['height'].'px' : $position['height'];
        }
        return $data;
    }

    /**
     * @param array|AdPositionEntity $position
     * @param array $items
     * @param bool $formatBody 是否预生成内容
     * @return array
     */
    public static function format(array|AdPositionEntity $position, array $items, bool $formatBody = true): array {
        $data = [];
        $now = time();
        $formatted = self::formatSize($position);
        foreach ($items as $item) {
            if ($item['status'] < 1 || ($item['start_at'] > 0 && $item['start_at'] > $now)
                || ($item['end_at'] > 0 && $item['end_at'] <= $now)) {
                continue;
            }
            $data[] = array_merge($formatted, $formatBody ? [
                'url' => $item['url'],
                'content' => TemplateCompiler::renderAd($item, $formatted)
            ] : [
                'url' => $item['url'],
                'type' => $item['type'],
                'content' => $item['content']
            ]);
        }
        return $data;
    }

    public static function manageList(string $keywords = '', int|string $position = 0) {
        return AdModel::query()
            ->with('position')
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->when(!empty($position), function ($query) use ($position) {
                if (is_numeric($position)) {
                    $query->where('position_id', $position);
                    return;
                }
                $positionId = AdPositionEntity::where('name', $position)
                    ->value('id');
                if (empty($positionId)) {
                    $query->isEmpty();
                    return;
                }
                $query->where('position_id', $positionId);
            })->orderBy('status', 'desc')->orderBy('id', 'desc')->page();
    }

    public static function manageGet(int $id) {
        return AdEntity::findOrThrow($id, '数据有误');
    }

    public static function manageSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AdModel::findOrNew($id);
        $model->load($data);
        if ($model->type % 2 > 0 && isset($data['content_url'])) {
            $model->content = $data['content_url'];
        }
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function manageRemove(int $id) {
        AdEntity::where('id', $id)->delete();
    }

    public static function managePositionList(string $keywords = '') {
        return AdPositionEntity::query()
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, ['name']);
            })->orderBy('status', 'desc')->orderBy('id', 'desc')->page();
    }

    public static function managePosition(int $id) {
        return AdPositionEntity::findOrThrow($id, '数据有误');
    }

    public static function managePositionSave(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = AdPositionEntity::findOrNew($id);
        $model->load($data);
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function managePositionRemove(int $id) {
        AdPositionEntity::where('id', $id)->delete();
        AdEntity::where('position_id', $id)->delete();
    }

    public static function positionAll() {
        return AdPositionEntity::query()->get('id', 'name');
    }
}