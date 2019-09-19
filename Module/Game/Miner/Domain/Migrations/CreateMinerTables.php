<?php
namespace Module\Game\Miner\Domain\Migrations;

use Module\Game\Miner\Domain\Model\AreaModel;
use Module\Game\Miner\Domain\Model\HouseModel;
use Module\Game\Miner\Domain\Model\MinerModel;
use Module\Game\Miner\Domain\Model\PlayerMinerModel;
use Module\Game\Miner\Domain\Model\PlayerModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateMinerTables extends Migration {
    public function up() {
        $this->append(PlayerModel::tableName(), function (Table $table) {
            $table->setComment('玩家表');
            $table->set('id')->pk(true);
            $table->set('user_id')->int()->notNull()->unsigned();
            $table->set('name')->varchar()->comment('名称');
            $table->set('house_id')
                ->smallInt(4)->unsigned()->defaultVal(0)->comment('房子类型');
            $table->timestamps();
        })->append(HouseModel::tableName(), function (Table $table) {
            $table->setComment('房子表');
            $table->set('id')->pk(true);
            $table->set('name')->varchar()->comment('名称');
            $table->set('amount')
                ->smallInt(4)->unsigned()->notNull()->comment('可住人数');
            $table->set('resume_speed')
                ->int(10, true, true)->comment('体力恢复速度');
            $table->set('price')->int(10, true)
                ->defaultVal(0)->comment('购买价格');
        })->append(AreaModel::tableName(), function (Table $table) {
            $table->setComment('矿场表');
            $table->set('id')->pk(true);
            $table->set('name')->varchar()->comment('名称');
            $table->set('earnings')
                ->smallInt(4)->unsigned()->notNull()->comment('收益');
            $table->set('price')->int(10, true)
                ->defaultVal(0)->comment('使用价格');
            $table->set('amount')->int(11, true)
                ->defaultVal(0)->comment('总产量');
            $table->timestamps();
        })->append(MinerModel::tableName(), function (Table $table) {
            $table->setComment('矿工表');
            $table->set('id')->pk(true);
            $table->set('name')->varchar()->comment('名称');
            $table->set('earnings')
                ->smallInt(4)->unsigned()->notNull()->comment('收益');
            $table->set('price')->int(10, true)
                ->defaultVal(0)->comment('价格');
            $table->set('max_ps')->int(11, true)
                ->defaultVal(0)->comment('最大体力');
            $table->set('max_money')->int(11, true)
                ->defaultVal(0)->comment('最大收益');
        })->append(PlayerMinerModel::tableName(), function (Table $table) {
            $table->setComment('玩家矿工表');
            $table->set('id')->pk(true);
            $table->set('player_id')->int(10, true, true);
            $table->set('miner_id')->int(10, true, true);
            $table->set('area_id')->int(10, true, true);
            $table->set('physical_strength')->comment('当前体力');
            $table->set('status')->tinyint(1)->defaultVal(0)->comment('当前状态');
            $table->timestamp('start_at');
            $table->timestamps();
        });
        parent::up();
    }
}