<?php
namespace Module\Game\TCG\Domain\Migrations;


use Module\Game\TCG\Domain\Model\CardGradeRuleModel;
use Module\Game\TCG\Domain\Model\CardModel;
use Module\Game\TCG\Domain\Model\GradeRuleModel;
use Module\Game\TCG\Domain\Model\JackpotModel;
use Module\Game\TCG\Domain\Model\LogModel;
use Module\Game\TCG\Domain\Model\PlayerCardModel;
use Module\Game\TCG\Domain\Model\PlayerModel;
use Module\Game\TCG\Domain\Model\PlayerSkillModel;
use Module\Game\TCG\Domain\Model\SkillModel;
use Module\Game\TCG\Domain\Model\StoreModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTheCardGameTables extends Migration {
    public function up() {
        $this->append(PlayerModel::tableName(), function (Table $table) {
            $table->comment('角色表');
            $table->id();
            $table->column('name')->varchar()->comment('游戏名');
            $table->uint('grade', 4)->default(0)->comment('等级');
            $table->uint('coin')->default(0)->comment('游戏金币');
            $table->uint('exp')->default(0)->comment('经验');
        })->append(GradeRuleModel::tableName(), function (Table $table) {
            $table->comment('等级规则表');
            $table->id();
            $table->column('name')->varchar()->comment('等级名');
            $table->uint('exp')->default(0)->comment('所需经验');
        })->append(PlayerCardModel::tableName(), function (Table $table) {
            $table->comment('用户拥有卡牌表');
            $table->id();
            $table->uint('user_id')->comment('');
            $table->uint('card_id')->comment('');
            $table->uint('grade', 4)->default(0)->comment('等级');
            $table->uint('exp')->default(0)->comment('经验');
            $table->uint('intellectual')->comment('智力，提升己方的攻击防御');
            $table->uint('charm')->comment('魅力，降低敌方的攻击防御');
            $table->uint('attack')->comment('攻击');
            $table->uint('defense')->comment('防御');
        })->append(CardModel::tableName(), function (Table $table) {
            $table->comment('卡牌表');
            $table->id();
            $table->column('name')->varchar()->comment('等级名');
            $table->uint('quality')->comment('卡牌品质');
            $table->uint('basic_intellectual')->comment('智力，提升己方的攻击防御');
            $table->uint('basic_charm')->comment('魅力，降低敌方的攻击防御');
            $table->uint('basic_attack')->comment('攻击');
            $table->uint('basic_defense')->comment('防御');
            $table->uint('exp')->default(0)->comment('转化经验');
            $table->uint('price')->default(0)->comment('出售价格');
            $table->uint('max_grade')->default(0)->comment('可以升级最大等级');
        })->append(CardGradeRuleModel::tableName(), function (Table $table) {
            $table->comment('卡牌等级表');
            $table->id();
            $table->column('name')->varchar()->comment('等级名');
            $table->uint('attr_plus')->default(0)->comment('属性增长%');
            $table->uint('exp')->default(0)->comment('所需经验');
            $table->uint('price')->default(0)->comment('所需价格');
        })->append(SkillModel::tableName(), function (Table $table) {
            $table->comment('技能表');
            $table->id();
            $table->column('name')->varchar()->comment('等级名');
            $table->column('description')->varchar()->default('')->comment('说明');
            $table->column('effect')->varchar()->default('')->comment('使用效果');
        })->append(PlayerSkillModel::tableName(), function (Table $table) {
            $table->comment('技能表');
            $table->id();
            $table->uint('user_id');
            $table->column('name')->varchar()->comment('等级名');
            $table->column('description')->varchar()->default('')->comment('说明');
            $table->column('effect')->varchar()->default('')->comment('使用效果');
        })->append(StoreModel::tableName(), function (Table $table) {
            $table->comment('商店表');
            $table->id();
            $table->uint('item_type', 4);
            $table->uint('item_id');
            $table->uint('price')->comment('价格');
        })->append(JackpotModel::tableName(), function (Table $table) {
            $table->comment('奖池表');
            $table->id();
            $table->uint('item_type', 4);
            $table->uint('item_id');
            $table->uint('chance', 4)->comment('抽中几率/ 10000');
        })->append(LogModel::tableName(), function (Table $table) {
            $table->comment('活动记录表');
            $table->id();
            $table->uint('user_id');
            $table->column('event_type')->varchar(10);
            $table->uint('item_type', 4)->default(0);
            $table->uint('item_id')->default(0);
            $table->column('remark')->varchar()->default('');
            $table->timestamp('created_at');
        })->autoUp();
    }
}