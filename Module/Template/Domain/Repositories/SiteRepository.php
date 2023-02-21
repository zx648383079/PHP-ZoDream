<?php
declare(strict_types=1);
namespace Module\Template\Domain\Repositories;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Module\Template\Domain\Model\PageModel;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\Model\SiteModel;
use Zodream\Database\Contracts\SqlBuilder;

final class SiteRepository extends CRUDRepository {

    protected static function query(): SqlBuilder {
        return SiteModel::query()->where('user_id', auth()->id());
    }

    protected static function createNew(): Model {
        return new SiteModel();
    }

    protected static function removeWith(int $id): bool {
        $ids =  PageModel::where('site_id', $id)->pluck('id');
        PageWeightModel::whereIn('page_id', $ids)->delete();
        PageModel::where('site_id', $id)->delete();
        return true;
    }

    public static function isUser(int $site): bool {
        return SiteModel::where('user_id', auth()->id())->where('id', $site)->count() > 0;
    }
}