<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Module\Template\Domain\Model\SiteWeightModel;
use Zodream\Database\Contracts\SqlBuilder;

final class SiteRepository extends CRUDRepository {

    protected static function query(): SqlBuilder {
        return SiteModel::query()->where('user_id', auth()->id());
    }

    protected static function createNew(): Model {
        return new SiteModel();
    }

    protected static function removeWith(int $id): bool {
        $ids = PageModel::where('site_id', $id)->pluck('id');
        PageWeightModel::whereIn('page_id', $ids)->delete();
        PageModel::where('site_id', $id)->delete();
        SiteWeightModel::where('site_id', $id)->delete();
        return true;
    }

    public static function isUser(int $site): bool {
        return SiteModel::where('user_id', auth()->id())->where('id', $site)->count() > 0;
    }

    public static function weightGroups(int $themeId, int $siteId) {
        $data = ThemeRepository::weightGroups($themeId);
        $weightItems = SiteWeightModel::where('site_id', $siteId)->where('is_share', 1)
            ->get('id', 'theme_weight_id', 'title');
        $sourceItems = [];
        foreach ($data as $i => $group) {
            foreach ($group['items'] as $j => $item) {
                $temp = $item->toArray();
                $data[$i]['items'][$j] = $temp;
                $sourceItems[$temp['id']] = $temp;
            }
        }
        $items = [];
        foreach ($weightItems as $item) {
            if (!isset($sourceItems[$item['theme_weight_id']])) {
                continue;
            }
            $temp = $sourceItems[$item['theme_weight_id']];
            $items[] = [
                'id' => $item['id'],
                'name' => $item['title'] ?: $temp['name'],
                'description' => $item['title'] ?: $temp['description'],
                'thumb' => $temp['thumb'],
                'editable' => $temp['editable']
            ];
        }
        $data[] = ['id' => 99, 'name' => '共享组件', 'items' => $items];
        return $data;
    }
}