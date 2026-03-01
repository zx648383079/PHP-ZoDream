<?php
declare(strict_types=1);
namespace Module\Team\Domain\Migrations;

use Module\Team\Domain\Entities\TeamEntity;
use Module\Team\Domain\Entities\TeamUserEntity;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

class CreateTeamTables extends Migration {

public function up(): void {
        $this->append(TeamEntity::tableName(), function (Table $table) {
            $table->comment('团队系统');
            $table->id();
            $table->uint('type', 1)->default(0)->comment('群类型');
            $table->string('name', 50)->comment('群名');
            $table->string('logo', 100)->comment('群LOGO');
            $table->string('description')->default('')
                ->comment('群说明');
            $table->uint('user_id')->comment('用户');
            $table->uint('open_type', 1)->default(0)->comment('群公开状态');
            $table->string('open_rule', 20)->default('')->comment('类型匹配的值');
            $table->uint('status', 1)->default(0)->comment('审核状态');
            $table->timestamps();
        })->append(TeamUserEntity::tableName(), function (Table $table) {
            $table->comment('团队成员系统');
            $table->id();
            $table->uint('team_id')->comment('群');
            $table->uint('user_id')->comment('用户');
            $table->string('name', 50)
                ->default('')->comment('群备注');
            $table->uint('role_id')->default(0)->comment('管理员等级');
            $table->uint('status', 1)
                ->default(5)->comment('用户状态/禁言或');
            $table->timestamps();
        })->autoUp();
    }
}