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
    public function up(): void {
        $this->append(PlayerModel::tableName(), function (Table $table) {
            $table->comment('玩家表');
            $table->id();
            $table->uint('user_id')->unsigned();
            $table->column('name')->varchar()->comment('名称');
            $table->column('house_id')
                ->short(4)->unsigned()->default(0)->comment('房子类型');
            $table->timestamps();
        })->append(HouseModel::tableName(), function (Table $table) {
            $table->comment('房子表');
            $table->id();
            $table->column('name')->varchar()->comment('名称');
            $table->column('amount')
                ->short(4)->unsigned()->comment('可住人数');
            $table->uint('resume_speed')->comment('体力恢复速度');
            $table->uint('price')
                ->default(0)->comment('购买价格');
        })->append(AreaModel::tableName(), function (Table $table) {
            $table->comment('矿场表');
            $table->id();
            $table->column('name')->varchar()->comment('名称');
            $table->column('earnings')
                ->short(4)->unsigned()->comment('收益');
            $table->uint('price')
                ->default(0)->comment('使用价格');
            $table->uint('amount')
                ->default(0)->comment('总产量');
            $table->timestamps();
        })->append(MinerModel::tableName(), function (Table $table) {
            $table->comment('矿工表');
            $table->id();
            $table->column('name')->varchar()->comment('名称');
            $table->column('earnings')
                ->short(4)->unsigned()->comment('收益');
            $table->uint('price')
                ->default(0)->comment('价格');
            $table->uint('max_ps')
                ->default(0)->comment('最大体力');
            $table->uint('max_money')
                ->default(0)->comment('最大收益');
        })->append(PlayerMinerModel::tableName(), function (Table $table) {
            $table->comment('玩家矿工表');
            $table->id();
            $table->uint('player_id');
            $table->uint('miner_id');
            $table->uint('area_id')->default(0);
            $table->column('physical_strength')->comment('当前体力');
            $table->uint('status', 2)->unsigned()->default(0)->comment('当前状态');
            $table->timestamp('start_at');
            $table->timestamps();
        })->autoUp();
    }
}