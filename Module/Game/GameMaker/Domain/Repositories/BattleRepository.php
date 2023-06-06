<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Exception;
use Zodream\Database\Schema\Table;

final class BattleRepository {

    public static function addProperty(Table $table) {
        $table->uint('hp')->default(1)->comment('血量');
        $table->uint('mp')->default(1)->comment('魔法值');
        $table->uint('att')->default(1)->comment('攻击力');
        $table->uint('def')->default(1)->comment('防御力');
    }

}