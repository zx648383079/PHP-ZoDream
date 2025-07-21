<?php
declare(strict_types=1);
namespace Module\Game\GameMaker\Domain\Adapters;

use Module\Game\GameMaker\Domain\Entities\BagEntity;
use Module\Game\GameMaker\Domain\Entities\CharacterEntity;
use Module\Game\GameMaker\Domain\Entities\CharacterIdentityEntity;
use Module\Game\GameMaker\Domain\Entities\CheckLogEntity;
use Module\Game\GameMaker\Domain\Entities\DescentEntity;
use Module\Game\GameMaker\Domain\Entities\MapEntity;
use Module\Game\GameMaker\Domain\Entities\MapItemEntity;
use Module\Game\GameMaker\Domain\Entities\OrganizationEntity;
use Module\Game\GameMaker\Domain\Entities\OrganizationStoreEntity;
use Module\Game\GameMaker\Domain\Entities\PlantingPlotsEntity;
use Module\Game\GameMaker\Domain\Entities\ProjectEntity;
use Module\Game\GameMaker\Domain\Entities\RanchEntity;
use Module\Game\GameMaker\Domain\Entities\RuleGradeEntity;
use Module\Game\GameMaker\Domain\Entities\StoreEntity;
use Module\Game\GameMaker\Domain\Entities\TaskEntity;
use Module\Game\GameMaker\Domain\Entities\TaskLogEntity;
use Module\Game\GameMaker\Domain\Entities\TeamEntity;
use Module\Game\GameMaker\Domain\Entities\TeamUserEntity;
use Module\Game\GameMaker\Domain\Entities\WarehouseEntity;
use Module\Game\GameMaker\Domain\Model\CharacterModel;
use Module\Game\GameMaker\Domain\Repositories\BattleRepository;
use Zodream\Database\Contracts\SqlBuilder;
use Zodream\Helpers\Str;

class GameAdapter implements IGameAdapter {

    public function __construct(
        protected ProjectEntity $project,
        protected CharacterEntity|null $character
    ) {
    }

    public function execute(string $command, array $data): mixed {
        $method = sprintf('execute%s', Str::studly($command));
        if (method_exists($this, $method)) {
            return $this->{$method}($data);
        }
        throw new \Exception(sprintf('[%s] is not support', $command));
    }

    protected function executeQuery() {
        return [
            'project' => $this->project,
            'character' => $this->character
        ];
    }

    protected function executeCharacterQuery() {
        return CharacterModel::with('identity')->where('project_id', $this->project['id'])
            ->where('user_id', auth()->id())->get();
    }

    protected function executeIdentityQuery() {
        return CharacterIdentityEntity::where('project_id', $this->project['id'])
            ->orderBy('id', 'desc')->get();
    }

    protected function executeDescentQuery() {
        return DescentEntity::where('project_id', $this->project['id'])
            ->orderBy('id', 'desc')->get();
    }

    protected function executeCharacterCreate(array $data) {
        if (empty($data['name'])) {
            throw new \Exception('昵称错误');
        }
        $count = CharacterEntity::where('project_id', $this->project['id'])
            ->where('nickname', $data['name'])->count();
        if ($count > 0) {
            throw new \Exception('昵称已存在');
        }
        $descent = DescentEntity::where('project_id', $this->project['id'])
            ->where('id', $data['descent_id'])->first();
        if (empty($descent)) {
            throw new \Exception('请选择血统');
        }
        $target = [
            'user_id' => auth()->id(),
            'project_id' => $this->project['id'],
            'identity_id' => intval($data['identity_id']),
            'descent_id' => intval($data['descent_id']),
            'nickname' => $data['name'],
            'sex' => $data['sex'] ?? 2,
            'grade' => 0,
            'money' => 0,
            'exp' => 0,
            'x' => intval(MapEntity::where('project_id', $this->project['id'])
                ->where('is_begin_map', 1)->value('id')),
            'y' => 0,
        ];
        foreach (BattleRepository::PROPERTY_KEYS as $key) {
            $target[$key] = $descent[$key];
        }
        return CharacterEntity::createOrThrow($target);
    }

    protected function executeCharacterNowOwn() {
        $model = $this->character->toArray();
        $model['is_checked'] = $this->executeCheckinTodayOwn();
        $model['message_count'] = $this->executeUnreadMessageCount();
        $model['can_upgrade'] = $this->executeCharacterCanUpgrade();
        return $model;
    }

    protected function executeUnreadMessageCount(): int {
        return 0;
    }

    protected function executeCharacterStatusOwn() {
        $model = $this->character->toArray();
        $model['can_upgrade'] = $this->executeCharacterCanUpgrade();
        $model['equip_items'] = [];
        return $model;
    }

    protected function executeCharacterCanUpgrade(): bool {
        $next = $this->nextGrade(intval($this->character['grade']));
        if (empty($next)) {
            return false;
        }
        return $this->character['exp'] >= $next['exp'];
    }

    protected function executeChatPublic() {
        return [];
    }

    protected function executeFarmOwn() {
        return [];
    }

    protected function executeRanchOwn() {
        return [];
    }

    protected function executeTeamQuery(array $data) {
        return $this->renderPage(TeamEntity::where('project_id', $this->project['id']), $data);
    }

    protected function executeTeamCreateOwn(array $data) {
        $teamId = intval($this->character['team_id']);
        if ($teamId > 0) {
            throw new \Exception('无法重复创建队伍');
        }
        // TODO
    }

    protected function executeTeamOwn() {
        $teamId = intval($this->character['team_id']);
        if ($teamId < 1) {
            throw new \Exception('无法查看');
        }
        $model = TeamEntity::where('project_id', $this->project['id'])
            ->where('id', $teamId)->first();
        $data = $model->toArray();
        $data['user_items'] = TeamUserEntity::where('project_id', $this->project['id'])
            ->where('team_id', $teamId)->get();
        $data['editable'] = $this->character['id'] == $model['user_id'];
        return $data;
    }

    protected function executeTeamDisbandOwn() {
        $teamId = intval($this->character['team_id']);
        if ($teamId < 1) {
            throw new \Exception('无法查看');
        }
        // TODO
    }

    protected function executeTeamExcludeOwn(array $data) {
        $teamId = intval($this->character['team_id']);
        if ($teamId < 1) {
            throw new \Exception('无法查看');
        }
        // TODO
    }

    protected function executeOrgQuery(array $data) {
        return $this->renderPage(OrganizationEntity::where('project_id', $this->project['id']), $data);
    }

    protected function executeOrgStoreQuery(array $data) {
        $orgId = intval($this->character['org_id']);
        if ($orgId < 1) {
            throw new \Exception('无法查看');
        }
        return $this->renderPage(OrganizationStoreEntity::where('project_id', $this->project['id'])
            ->where('org_id', $orgId), $data);
    }

    protected function executeStoreQuery(array $data) {
        return $this->renderPage(StoreEntity::where('project_id', $this->project['id']), $data);
    }

    protected function executeBagOwn(array $data) {
        return $this->renderPage(BagEntity::where('project_id', $this->project['id'])
            ->where('user_id', $this->character['id']), $data);
    }

    protected function executeWarehouseOwn(array $data) {
        return $this->renderPage(WarehouseEntity::where('project_id', $this->project['id'])
            ->where('user_id', $this->character['id']), $data);
    }

    protected function executeFarmQuery() {
        return PlantingPlotsEntity::where('project_id', $this->project['id'])
            ->where('user_id', $this->character['id'])->get();
    }

    protected function executeRanchQuery() {
        return RanchEntity::where('project_id', $this->project['id'])
            ->where('user_id', $this->character['id'])->get();
    }

    protected function executeTaskQuery() {
        return TaskEntity::where('project_id', $this->project['id'])
            ->where('user_id', $this->character['id'])->get();
    }

    protected function executeMapDungeonQuery(array $data) {
        $id = intval($data['id']);
        return [];
    }

    protected function executeBattle(array $data) {
        $id = intval($data['id']);
        $enemy = [];
        $own = [];
        $log_items = [];
        return compact('enemy', 'own', 'log_items');
    }

    protected function executeCheckinOwn() {
        $last = CheckLogEntity::where('user_id', $this->character['id'])
            ->where('project_id', $this->project['id'])->orderBy('created_at', 'desc')->first();
        $today = strtotime(date('Y-m-d 00:00:00'));
        if ($last && $last->getAttributeSource('created_at') > $today) {
            throw new \Exception('已签到');
        }
        $running = 1;
        if (!empty($last) &&
            $last->getAttributeSource('created_at') > $today - 86400) {
            $running = $last->running + 1;
        }
        $reward = [
            '签到成功',
            sprintf('获得 %s x %d', '金钱', 1),
            sprintf('获得 %s x %d', '金钱', 1),
            sprintf('获得 %s x %d', '金钱', 1)
        ];
        $model = CheckLogEntity::createOrThrow([
            'project_id' => $this->project['id'],
            'user_id' => $this->character['id'],
            'type' => 0,
            'running' => $running,
            'reward' => implode("\n", $reward),
            'created_at' => time()
        ]);
        return $reward;
    }

    protected function executeCheckinTodayOwn(): bool {
        $todayStart = strtotime(date('Y-m-d 00:00:00'));
        return CheckLogEntity::where('project_id', $this->project['id'])
            ->where('user_id', $this->character['id'])->where('created_at', '>=', $todayStart)
            ->count() > 0;
    }

    protected function executeTaskOwn(array $data) {
        return $this->renderPage(TaskLogEntity::query()->where('project_id', $this->project['id'])
            ->where('user_id', $this->character['id']), $data);
    }

    protected function executeMessageOwn() {
        return [];
    }

    protected function executeMapMoveOwn(array $data) {
        $to = intval($data['map'] ?? 0);
        $current = $this->character['x'];
        if ($to > 0) {
            // 判断是是否与当前位置相邻
            $current = $to;
        }
        $model = MapEntity::where('project_id', $this->project['id'])->where('id', $current)
        ->first();
        $items = MapItemEntity::where('project_id', $this->project['id'])->where('map_id', $current)
        ->get();
        $npc = [];
        $monsters = [];
        $items = [];
        $link_maps = [];

        return compact('npc', 'monsters', 'items', 'link_maps');
    }

    protected function executeMapPickOwn(array $data) {

        return [
            sprintf('获得 %s x %d', '石头', 1)
        ];
    }

    protected function executeMapInquireOwn(array $data) {

        $command = 'select';
        $data = [
        ];
        return compact('command', 'data');
    }

    protected function executeBattleOwn(array $data) {

        $enemy = [];
        $own = [];
        $log_items = [];
        return compact('enemy', 'own', 'log_items');
    }


    protected function renderPage(SqlBuilder $builder, array $queries) {
        if (!isset($queries['page'])) {
            $queries['page'] = 1;
        } else {
            $queries['page'] = max(intval($queries['page']), 1);
        }
        if (!isset($queries['per_page'])) {
            $queries['per_page'] = 20;
        } else {
            $queries['per_page'] = max(intval($queries['per_page']), 1);
        }
        return $builder->page(intval($queries['per_page']), 'page', intval($queries['page']));
    }

    protected function identityName(int $id): string {
        return '';
    }

    protected function grade(int $grade): RuleGradeEntity {
        return new RuleGradeEntity();
    }

    protected function nextGrade(int $grade): RuleGradeEntity|null {
        return null;
    }
}