<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Module\OnlineTV\Domain\Models\AreaModel;
use Zodream\Database\Contracts\SqlBuilder;

final class AreaRepository extends CRUDRepository {

    protected static function query(): SqlBuilder
    {
        return AreaModel::query();
    }

    protected static function createNew(): Model
    {
        return new AreaModel();
    }

    public static function all() {
        return self::query()->get();
    }

}
