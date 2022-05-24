<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\Model;
use Domain\Repositories\CRUDRepository;
use Exception;
use Module\OnlineTV\Domain\Models\LiveModel;
use Zodream\Database\Contracts\SqlBuilder;

final class LiveRepository extends CRUDRepository {

    protected static function query(): SqlBuilder
    {
        return LiveModel::query();
    }

    protected static function createNew(): Model
    {
        return new LiveModel();
    }
}
