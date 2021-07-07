<?php
declare(strict_types=1);
namespace Module\Forum\Domain\Repositories;

use Domain\Repositories\ActionRepository;
use Module\Forum\Domain\Model\ThreadLogModel;
use Zodream\Database\Contracts\SqlBuilder;

class LogRepository extends ActionRepository {

    protected static function query(): SqlBuilder
    {
        return ThreadLogModel::query();
    }
}