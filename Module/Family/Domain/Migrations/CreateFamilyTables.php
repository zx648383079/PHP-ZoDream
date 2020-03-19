<?php
namespace Module\Family\Domain\Migrations;

use Module\Family\Domain\Model\ClanModel;
use Module\Family\Domain\Model\FamilyLogModel;
use Module\Family\Domain\Model\FamilySpouseModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\Family\Domain\Model\FamilyModel;

class CreateFamilyTables extends Migration {

    public function up() {
        $this->append(ClanModel::tableName(), function(Table $table) {
            $table->setComment('家族表');
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull();
            $table->set('cover')->varchar(100)->notNull();
            $table->set('description')->varchar()->defaultVal('');
            $table->set('status')->bool();
            $table->set('user_id')->unsigned()->int();
            $table->timestamps();
        })->append(FamilyModel::tableName(), function(Table $table) {
            $table->setComment('家族人员表');
            $table->set('id')->pk(true);
            $table->set('name')->varchar(100)->notNull();
            $table->set('secondary_name')->varchar(100)->defaultVal('')->comment('表字');
            $table->set('sex')->tinyint(1)->defaultVal(0)->comment('0: 其他，1: 女 2：男');
            $table->set('birth_at')->varchar(20)->defaultVal('')->comment('出生时间');
            $table->set('death_at')->varchar(20)->defaultVal('')->comment('去世时间');
            $table->set('parent_id')->int()->unsigned()->defaultVal(0)->comment('生父');
            $table->set('mother_id')->int()->unsigned()->defaultVal(0)->comment('生母');
            $table->set('spouse_id')->int()->unsigned()->defaultVal(0)->comment('配偶');
            $table->set('clan_id')->int()->unsigned()->defaultVal(0);
            $table->set('level_id')->smallInt(5)->unsigned()->defaultVal(0)->comment('第几代');
            $table->set('lifetime')->text()->comment('生平');
            $table->set('status')->bool();
            $table->set('user_id')->int()->unsigned()->defaultVal(0);
            $table->timestamps();
        })->append(FamilySpouseModel::tableName(), function(Table $table) {
            $table->setComment('家族婚姻表');
            $table->set('id')->pk(true);
            $table->set('family_id')->int()->unsigned()->notNull();
            $table->set('spouse_id')->int()->unsigned()->notNull();
            $table->set('relation')->tinyint(1)->defaultVal(0)->comment('关系');
            $table->set('status')->bool();
            $table->set('start_at')->varchar(20)->defaultVal('')->comment('出生时间');
            $table->set('end_at')->varchar(20)->defaultVal('')->comment('去世时间');
            $table->timestamps();
        })->append(FamilyLogModel::tableName(), function(Table $table) {
            $table->setComment('家族历史记录表');
            $table->set('id')->pk(true);
            $table->set('family_id')->int()->unsigned()->notNull();
            $table->set('relation_family')->varchar()->defaultVal('');
            $table->set('event')->varchar(30)->defaultVal('');
            $table->set('remark')->text();
            $table->set('edit_user')->int()->notNull();
            $table->timestamp('created_at');
        });
        parent::up();
    }

}