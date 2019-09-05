<?php
namespace Module\Game\TCG\Domain\Migrations;


use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTheCardGameTables extends Migration {
    public function up() {
        $this->append('user', function (Table $table) {
            $table->setComment('角色表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('游戏名');
            $table->set('grade')->smallInt(4)->unsigned()->defaultVal(0)->comment('等级');
            $table->set('coin')->int()->unsigned()->defaultVal(0)->comment('游戏金币');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('经验');
        })->append('grade_rule', function (Table $table) {
            $table->setComment('等级规则表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('等级名');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('所需经验');
        })->append('user_card', function (Table $table) {
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
        })->append('card', function (Table $table) {
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
        })->append('card_grade_rule', function (Table $table) {
            $table->setComment('卡牌等级表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('等级名');
            $table->set('attr_plus')->int()->unsigned()->defaultVal(0)->comment('属性增长%');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('所需经验');
            $table->set('price')->int()->unsigned()->defaultVal(0)->comment('所需价格');
        })->append('card_grade_rule', function (Table $table) {
            $table->setComment('卡牌等级表');
            $table->set('id')->pk();
            $table->set('name')->varchar()->comment('等级名');
            $table->set('attr_plus')->int()->unsigned()->defaultVal(0)->comment('属性增长%');
            $table->set('exp')->int()->unsigned()->defaultVal(0)->comment('所需经验');
            $table->set('price')->int()->unsigned()->defaultVal(0)->comment('所需价格');
        })->append('');
        parent::up();
    }
}