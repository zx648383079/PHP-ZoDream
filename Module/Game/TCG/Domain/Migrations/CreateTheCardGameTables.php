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
            $table->setComment('角色表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('游戏名');
            $table->set('grade')->smallInt(4)->unsigned()->defaultVal(0)->comment('等级');
            $table->set('coin')->int()->unsigned()->defaultVal(0)->comment('游戏金币');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('经验');
        })->append(GradeRuleModel::tableName(), function (Table $table) {
            $table->setComment('等级规则表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('等级名');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('所需经验');
        })->append(PlayerCardModel::tableName(), function (Table $table) {
            $table->setComment('用户拥有卡牌表');
            $table->set('id')->pk();
            $table->set('user_id')->int()->comment('');
            $table->set('card_id')->int()->comment('');
            $table->set('grade')->smallInt(4)->unsigned()->defaultVal(0)->comment('等级');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('经验');
            $table->set('intellectual')->int()->unsigned()->comment('智力，提升己方的攻击防御');
            $table->set('charm')->int()->unsigned()->comment('魅力，降低敌方的攻击防御');
            $table->set('attack')->int()->unsigned()->comment('攻击');
            $table->set('defense')->int()->unsigned()->comment('防御');
        })->append(CardModel::tableName(), function (Table $table) {
            $table->setComment('卡牌表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('等级名');
            $table->set('quality')->int()->comment('卡牌品质');
            $table->set('basic_intellectual')->int()->unsigned()->comment('智力，提升己方的攻击防御');
            $table->set('basic_charm')->int()->unsigned()->comment('魅力，降低敌方的攻击防御');
            $table->set('basic_attack')->int()->unsigned()->comment('攻击');
            $table->set('basic_defense')->int()->unsigned()->comment('防御');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('转化经验');
            $table->set('price')->int()->unsigned()->defaultVal(0)->comment('出售价格');
            $table->set('max_grade')->int()->unsigned()->defaultVal(0)->comment('可以升级最大等级');
        })->append(CardGradeRuleModel::tableName(), function (Table $table) {
            $table->setComment('卡牌等级表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('等级名');
            $table->set('attr_plus')->int()->unsigned()->defaultVal(0)->comment('属性增长%');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('所需经验');
            $table->set('price')->int()->unsigned()->defaultVal(0)->comment('所需价格');
        })->append(SkillModel::tableName(), function (Table $table) {
            $table->setComment('技能表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('等级名');
            $table->set('description')->varchar()->defaultVal('')->comment('说明');
            $table->set('effect')->varchar()->defaultVal('')->comment('使用效果');
        })->append(PlayerSkillModel::tableName(), function (Table $table) {
            $table->setComment('技能表');
            $table->set('id')->pk();
            $table->set('user_id')->int(10, true, true);
            $table->set('name')->varchar()->comment('等级名');
            $table->set('description')->varchar()->defaultVal('')->comment('说明');
            $table->set('effect')->varchar()->defaultVal('')->comment('使用效果');
        })->append(StoreModel::tableName(), function (Table $table) {
            $table->setComment('商店表');
            $table->set('id')->pk();
            $table->set('item_type')->int(10, true, true);
            $table->set('item_id')->int(10, true, true);
            $table->set('price')->int(10, true, true)->comment('价格');
        })->append(JackpotModel::tableName(), function (Table $table) {
            $table->setComment('奖池表');
            $table->set('id')->pk();
            $table->set('item_type')->int(10, true, true);
            $table->set('item_id')->int(10, true, true);
            $table->set('chance')->smallInt(4)->comment('抽中几率/ 10000');
        })->append(LogModel::tableName(), function (Table $table) {
            $table->setComment('活动记录表');
            $table->set('id')->pk();
            $table->set('user_id')->int(10, true, true);
            $table->set('event_type')->varchar(10)->notNull();
            $table->set('item_type')->int(10, true, true)->defaultVal(0);
            $table->set('item_id')->int(10, true, true)->defaultVal(0);
            $table->set('remark')->varchar()->defaultVal('');
            $table->timestamp('created_at');
        });
        parent::up();
    }
}