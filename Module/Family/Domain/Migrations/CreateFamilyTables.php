<?php
namespace Module\Family\Domain\Migrations;

use Module\Family\Domain\Model\ClanMetaModel;
use Module\Family\Domain\Model\ClanModel;
use Module\Family\Domain\Model\FamilyLogModel;
use Module\Family\Domain\Model\FamilySpouseModel;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;
use Module\Family\Domain\Model\FamilyModel;

class CreateFamilyTables extends Migration {

    public function up() {
        $this->append(ClanModel::tableName(), function(Table $table) {
            $table->comment('家族表');
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('cover')->varchar(100);
            $table->column('description')->varchar()->default('');
            $table->column('status')->bool()->default(0);
            $table->uint('user_id');
            $table->column('modify_at')->varchar(50)->default('');
            $table->timestamps();
        })->append(ClanMetaModel::tableName(), function(Table $table) {
            $table->comment('家族附录');
            $table->id();
            $table->uint('clan_id');
            $table->column('name')->varchar(100);
            $table->column('content')->text();
            $table->uint('user_id');
            $table->column('author')->varchar(30)->default('');
            $table->column('position')->tinyint(1)->unsigned()->default(10);
            $table->column('modify_at')->varchar(50)->default('');
            $table->timestamps();
        })->append(FamilyModel::tableName(), function(Table $table) {
            $table->comment('家族人员表');
            $table->id();
            $table->column('name')->varchar(100);
            $table->column('secondary_name')->varchar(100)->default('')->comment('表字');
            $table->column('sex')->tinyint(1)->default(0)->comment('0: 其他，1: 女 2：男');
            $table->column('birth_at')->varchar(20)->default('')->comment('出生时间');
            $table->column('death_at')->varchar(20)->default('')->comment('去世时间');
            $table->uint('parent_id')->default(0)->comment('生父');
            $table->uint('mother_id')->default(0)->comment('生母');
            $table->uint('spouse_id')->default(0)->comment('配偶');
            $table->uint('clan_id')->default(0);
            $table->short('level_id')->unsigned()->default(0)->comment('第几代');
            $table->column('lifetime')->text()->nullable()->comment('生平');
            $table->column('status')->bool()->default(0);
            $table->uint('user_id')->default(0);
            $table->timestamps();
        })->append(FamilySpouseModel::tableName(), function(Table $table) {
            $table->comment('家族婚姻表');
            $table->id();
            $table->uint('family_id');
            $table->uint('spouse_id');
            $table->column('relation')->tinyint(1)->default(0)->comment('关系');
            $table->column('status')->bool()->default(0);
            $table->column('start_at')->varchar(20)->default('')->comment('出生时间');
            $table->column('end_at')->varchar(20)->default('')->comment('去世时间');
            $table->timestamps();
        })->append(FamilyLogModel::tableName(), function(Table $table) {
            $table->comment('家族历史记录表');
            $table->id();
            $table->uint('family_id');
            $table->column('relation_family')->varchar()->default('');
            $table->column('event')->varchar(30)->default('');
            $table->column('remark')->text();
            $table->uint('edit_user');
            $table->timestamp('created_at');
        })->autoUp();
    }

}