<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Migrations;

use Module\Game\GameMaker\Domain\Entities\ActionLogEntity;
use Module\Game\GameMaker\Domain\Entities\AuctionEntity;
use Module\Game\GameMaker\Domain\Entities\BagEntity;
use Module\Game\GameMaker\Domain\Entities\CharacterEntity;
use Module\Game\GameMaker\Domain\Entities\CharacterIdentityEntity;
use Module\Game\GameMaker\Domain\Entities\CharacterStateEntity;
use Module\Game\GameMaker\Domain\Entities\FinancialEntity;
use Module\Game\GameMaker\Domain\Entities\FriendEntity;
use Module\Game\GameMaker\Domain\Entities\HouseEntity;
use Module\Game\GameMaker\Domain\Entities\IndigenousEntity;
use Module\Game\GameMaker\Domain\Entities\ItemEntity;
use Module\Game\GameMaker\Domain\Entities\MapAreaEntity;
use Module\Game\GameMaker\Domain\Entities\MapEntity;
use Module\Game\GameMaker\Domain\Entities\MapIndigenousEntity;
use Module\Game\GameMaker\Domain\Entities\MapItemEntity;
use Module\Game\GameMaker\Domain\Entities\MessageEntity;
use Module\Game\GameMaker\Domain\Entities\MineEntity;
use Module\Game\GameMaker\Domain\Entities\MinerEntity;
use Module\Game\GameMaker\Domain\Entities\OrganizationEntity;
use Module\Game\GameMaker\Domain\Entities\OrganizationMemberEntity;
use Module\Game\GameMaker\Domain\Entities\OrganizationStoreEntity;
use Module\Game\GameMaker\Domain\Entities\PlantingPlotsEntity;
use Module\Game\GameMaker\Domain\Entities\ProjectEntity;
use Module\Game\GameMaker\Domain\Entities\ProjectMetaEntity;
use Module\Game\GameMaker\Domain\Entities\RanchEntity;
use Module\Game\GameMaker\Domain\Entities\RuleGradeEntity;
use Module\Game\GameMaker\Domain\Entities\RulePlantingPlotsEntity;
use Module\Game\GameMaker\Domain\Entities\RulePrizeEntity;
use Module\Game\GameMaker\Domain\Entities\SkillEntity;
use Module\Game\GameMaker\Domain\Entities\StoreEntity;
use Module\Game\GameMaker\Domain\Entities\TaskEntity;
use Module\Game\GameMaker\Domain\Entities\TaskItemEntity;
use Module\Game\GameMaker\Domain\Entities\TeamEntity;
use Module\Game\GameMaker\Domain\Entities\TeamUserEntity;
use Module\Game\GameMaker\Domain\Entities\WarehouseEntity;
use Module\Game\GameMaker\Domain\Repositories\BattleRepository;
use Zodream\Database\Migrations\Migration;
use Zodream\Database\Schema\Table;

final class CreateGameMakerTables extends Migration {

    public function up() {
        $this->append(ProjectEntity::tableName(), function (Table $table) {
            $table->id();
            $table->uint('user_id');
            $table->string('name');
            $table->string('logo')->default('');
            $table->string('description')->default('');
            $table->uint('status', 1)->default(0);
            $table->timestamps();
        })->append(ProjectMetaEntity::tableName(), function (Table $table) {
            $table->comment('项目设置等配置信息');
            $table->id();
            $table->uint('project_id');
            $table->string('name');
            $table->text('content');
        })->append(RulePrizeEntity::tableName(), function (Table $table) {
            $table->comment('抽奖规则');
            $table->id();
            $table->uint('project_id');
            $table->uint('item_id');
            $table->uint('probability')->comment('中奖概率');
        })->append(CharacterEntity::tableName(), function (Table $table) {
            $table->comment('角色表');
            $table->id();
            $table->uint('user_id');
            $table->uint('project_id');
            $table->uint('identity_id')->default(0);
            $table->string('nickname')->comment('游戏名');
            $table->bool('sex')->default(0)->comment('游戏名,男/女');
            $table->uint('grade', 4)->default(0)->comment('等级');
            $table->string('money', 20)->default(0)->comment('游戏金币');
            $table->string('exp', 20)->default(0)->comment('当前等级剩余经验');
            $table->uint('x')->default(0)->comment('大地图位置x');
            $table->uint('y')->default(0)->comment('副本位置y');
            BattleRepository::addProperty($table);
            $table->timestamps();
        })->append(CharacterStateEntity::tableName(), function (Table $table) {
            $table->comment('角色状态表');
            $table->id();
            $table->uint('project_id');
            $table->string('name');
            $table->timestamp('start_at');
            $table->timestamp('end_at');
        })->append(CharacterIdentityEntity::tableName(), function (Table $table) {
            $table->comment('身份职位表');
            $table->id();
            $table->uint('project_id');
            $table->string('name');
            $table->string('image')->default('');
            $table->string('description')->default('');
            // TODO 一些属性的默认值
            BattleRepository::addProperty($table);
            $table->uint('status', 1)->default(0)->comment('状态');
        })->append(RuleGradeEntity::tableName(), function (Table $table) {
            $table->comment('角色等级规则表');
            $table->id();
            $table->uint('project_id');
            $table->string('name')->default('')->comment('等级名');
            $table->uint('grade', 4)->comment('等级名');
            $table->string('exp', 20)->default(0)->comment('上一级升所需经验');
        })->append(HouseEntity::tableName(), function (Table $table) {
            $table->comment('房产');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id')->default(0);
            $table->string('name')->comment('房子名');
            $table->timestamps();
        })->append(FinancialEntity::tableName(), function (Table $table) {
            $table->comment('理财产品');
            $table->id();
            $table->uint('project_id');
            $table->string('name')->comment('名称');
            $table->string('description')->default('')->comment('名称');
            $table->uint('rule_type', 1)->default(0);
            $table->string('rule', 1)->default(0);
            $table->timestamps();
        })->append(MineEntity::tableName(), function (Table $table) {
            $table->comment('矿场');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id')->default(0);
            $table->string('name')->comment('名称');
            $table->string('description')->default('')->comment('名称');
            $table->uint('earnings', 4)->comment('收益');
            $table->uint('price')
                ->default(0)->comment('使用价格');
            $table->uint('amount')
                ->default(0)->comment('总产量');
            $table->timestamps();
        })->append(MinerEntity::tableName(), function (Table $table) {
            $table->comment('矿场矿工');
            $table->id();
            $table->uint('project_id');
            $table->uint('indigenous_id');
            $table->uint('user_id');
            $table->string('name')->comment('名称');
            $table->uint('earnings', 4)->comment('收益');
            $table->uint('price')
                ->default(0)->comment('价格');
            $table->uint('max_ps')
                ->default(0)->comment('最大体力');
            $table->uint('max_money')
                ->default(0)->comment('最大收益');
            $table->timestamps();
        })->append(RulePlantingPlotsEntity::tableName(), function (Table $table) {
            $table->comment('种植地块规则');
            $table->id();
            $table->uint('project_id');
            $table->uint('index', 1)->default(0)->comment('第几块');
            $table->uint('grade', 1)->default(0)->comment('0 开垦价格，其他表示升级');
            $table->string('grade_alias', 20)->default('')->comment('每一级别名');
            $table->uint('price')->default(0);
            $table->uint('time_scale')->comment('时间缩短比例');
            $table->uint('yield_scale', 2)->comment('产量提升比例');
            $table->timestamps();
        })->append(PlantingPlotsEntity::tableName(), function (Table $table) {
            $table->comment('种植地块');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('plant_id')->default(0);
            $table->uint('index', 1)->default(0);
            $table->uint('grade', 1)->default(1);
            $table->timestamp('plant_at')->comment('种植时间');
            $table->timestamp('harvest_at')->comment('收获时间');
            $table->uint('yield_count', 2)->comment('预计产量');
            $table->timestamps();
        })->append(RanchEntity::tableName(), function (Table $table) {
            $table->comment('养殖牧场');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('animal_id')->default(0);
            $table->timestamp('farm_at')->comment('养殖时间');
            $table->timestamp('harvest_at')->comment('收获时间');
            $table->uint('yield_count', 2)->comment('预计产量');
            $table->timestamps();
        })->append(MapEntity::tableName(), function (Table $table) {
            $table->comment('地图区域');
            $table->id();
            $table->uint('project_id');
            $table->uint('area_id')->default(0);
            $table->string('name');
            $table->string('description')->default('');
            $table->uint('south_id')->default(0)->comment('南边区域');
            $table->uint('east_id')->default(0)->comment('东边区域');
            $table->uint('north_id')->default(0)->comment('北边区域');
            $table->uint('west_id')->default(0)->comment('西边区域');
            $table->uint('x')->default(0);
            $table->uint('y')->default(0);
        })->append(MapAreaEntity::tableName(), function (Table $table) {
            $table->comment('大地图表');
            $table->id();
            $table->uint('project_id');
            $table->string('name');
            $table->uint('parent_id');
            $table->uint('x')->default(0);
            $table->uint('y')->default(0);
            $table->uint('width')->default(0);
            $table->uint('height')->default(0);
        })->append(MapItemEntity::tableName(), function (Table $table) {
            $table->comment('区域物品');
            $table->id();
            $table->uint('project_id');
            $table->uint('map_id');
            $table->uint('item_id');
            $table->uint('amount')->default(1);
            $table->timestamp('expired_at');
            $table->timestamp('refresh_at');
            $table->timestamps();
        })->append(MapIndigenousEntity::tableName(), function (Table $table) {
            $table->comment('区域npc,怪物');
            $table->id();
            $table->uint('project_id');
            $table->uint('map_id');
            $table->uint('indigenous_id');
            $table->uint('refresh_space')->default(0)->comment('怪物击杀后自动刷新时间');
            $table->uint('status', 1)->default(0);
            $table->timestamp('refresh_at');
            $table->timestamps();
        })->append(IndigenousEntity::tableName(), function (Table $table) {
            $table->comment('土著，包括npc、怪物、boss');
            $table->id();
            $table->uint('project_id');
            $table->string('name');
            $table->string('description');
            BattleRepository::addProperty($table);
            $table->timestamps();
        })->append(ItemEntity::tableName(), function (Table $table) {
            $table->comment('物品');
            $table->id();
            $table->uint('project_id');
            $table->uint('type');
            $table->string('name');
            $table->string('description');
        })->append(SkillEntity::tableName(), function (Table $table) {
            $table->comment('技能表');
            $table->id();
            $table->uint('project_id');
            $table->string('name');
            $table->string('description');
        })->append(WarehouseEntity::tableName(), function (Table $table) {
            $table->comment('仓库');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('item_id');
            $table->uint('amount')->default(1);
        })->append(BagEntity::tableName(), function (Table $table) {
            $table->comment('背包');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('item_id');
            $table->uint('amount')->default(1);
        })->append(OrganizationEntity::tableName(), function (Table $table) {
            $table->comment('组织势力');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('name');
            $table->string('position_alias')->default('')->comment('职务别名');
        })->append(OrganizationMemberEntity::tableName(), function (Table $table) {
            $table->comment('组织成员');
            $table->id();
            $table->uint('project_id');
            $table->uint('org_id');
            $table->uint('user_id');
            $table->string('contribution', 20)->default('');
            $table->uint('position', 2)->default(0)->comment('职务');
        })->append(OrganizationStoreEntity::tableName(), function (Table $table) {
            $table->comment('组织商店');
            $table->id();
            $table->uint('project_id');
            $table->uint('org_id');
            $table->uint('user_id');
            $table->uint('item_id');
            $table->uint('amount');
            $table->uint('price');
        })->append(StoreEntity::tableName(), function (Table $table) {
            $table->comment('商店');
            $table->id();
            $table->uint('project_id');
            $table->uint('item_id');
            $table->uint('amount');
            $table->uint('price');
            $table->timestamps();
        })->append(AuctionEntity::tableName(), function (Table $table) {
            $table->comment('寄售商店');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('item_id');
            $table->uint('amount');
            $table->uint('price');
            $table->timestamps();
        })->append(TaskEntity::tableName(), function (Table $table) {
            $table->comment('任务');
            $table->id();
            $table->uint('project_id');
            $table->string('title');
            $table->string('description')->default('');
            $table->uint('type', 1)->default(0);
            $table->string('gift')->default('');
            $table->string('before')->default('');
            $table->timestamps();
        })->append(TaskItemEntity::tableName(), function (Table $table) {
            $table->comment('任务详细');
            $table->id();
            $table->uint('project_id');
            $table->uint('task_id');
            $table->uint('npc_id');
            $table->string('text');
            $table->string('option')->default('');
            $table->timestamps();
        })->append(ActionLogEntity::tableName(), function(Table $table) {
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('item_type', 1)->default(0);
            $table->uint('item_id');
            $table->uint('action');
            $table->string('remark')->default('');
            $table->timestamp('created_at');
        })->append(MessageEntity::tableName(), function(Table $table) {
            $table->comment('聊天频道');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('receive_id');
            $table->uint('item_type', 1)->default(0)->comment('所处频道');
            $table->uint('item_id')->default(0);
            $table->string('content', 400)->comment('内容');
            $table->string('extra_rule', 400)
                ->default('')->comment('附加替换规则');
            $table->timestamp('created_at');
        })->append(FriendEntity::tableName(), function(Table $table) {
            $table->comment('好友');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->uint('belong_id');
            $table->uint('type', 1)->default(0);
            $table->timestamp('created_at');
        })->append(TeamEntity::tableName(), function(Table $table) {
            $table->comment('组队');
            $table->id();
            $table->uint('project_id');
            $table->uint('user_id');
            $table->string('name');
            $table->timestamp('created_at');
        })->append(TeamUserEntity::tableName(), function(Table $table) {
            $table->comment('组队人');
            $table->id();
            $table->uint('project_id');
            $table->uint('team_id');
            $table->uint('user_id');
            $table->timestamp('created_at');
        })->autoUp();
    }
}