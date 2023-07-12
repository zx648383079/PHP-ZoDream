<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Repositories;

use Exception;
use Zodream\Database\Schema\Table;

final class BattleRepository {

    const PROPERTY_KEYS = ['hp', 'mp', 'att', 'def', 'crt', 'lck', 'dex', 'chr', 'int'];

    public static function addProperty(Table $table) {
        $table->uint('hp')->default(1)->comment('血量');
        $table->uint('mp')->default(1)->comment('魔法值');
        $table->uint('att')->default(1)->comment('攻击力');
        $table->uint('def')->default(1)->comment('防御力');
        $table->uint('crt', 3)->default(1)->comment('暴击率');
        $table->uint('lck')->default(1)->comment('幸运值');
        $table->uint('dex')->default(1)->comment('命中率');
        $table->uint('chr')->default(1)->comment('魅力值');
        $table->uint('int')->default(1)->comment('智力');
    }

}