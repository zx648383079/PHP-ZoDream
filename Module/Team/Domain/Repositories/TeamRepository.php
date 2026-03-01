<?php
declare(strict_types=1);
namespace Module\Team\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Team\Domain\Entities\TeamUserEntity;
use Module\Team\Domain\Entities\TeamEntity;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\Auth\Domain\Repositories\ApplyRepository;
use Domain\Constants;
use Module\Team\Domain\Events\DisbandTeam;
use Infrastructure\LinkRule;
use Module\Auth\Domain\Repositories\BulletinRepository;

final class TeamRepository {
    public static function all() {
        $ids = TeamUserEntity::where('user_id', auth()->id())->pluck('team_id');
        return self::getAny($ids);
    }


    public static function getAny(array $idItems): array {
        if (empty($idItems)) {
            return [];
        }
        return TeamEntity::whereIn('id', $idItems)->get('id', 'name', 'logo', 'description');
    }

    public static function detail(int $id) {
        if (!self::canable($id)) {
            throw new \Exception('无权限查看');
        }
        $model =TeamEntity::findOrThrow($id, '群不存在');
        $model->users = static::users($id);
        return $model;
    }

    public static function users(int $team, string $keywords = '') {
        return TeamUserEntity::with('user')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->where('team_id', $team)->page();
    }

    public static function userAny(int $team, array $userItems): array {
        if (empty($userItems)) {
            return [];
        }
        return TeamUserEntity::whereIn('user_id', $userItems)
            ->where('team_id', $team)
            ->get();
    }

    public static function search(string $keywords = '') {
        $ids = TeamUserEntity::where('user_id', auth()->id())->pluck('team_id');
        return TeamEntity::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name']);
        })->whereNotIn('id', $ids)->page();
    }

    public static function canable(int $id) {
        return TeamUserEntity::where('user_id', auth()->id())->where('team_id', $id)
            ->count() > 0;
    }

    public static function manageable(int $id) {
        return TeamEntity::where('id', $id)
            ->where('user_id', auth()->id())->count() > 0;
    }

    public static function agree(int $user, int $id) {
        if (!static::manageable($id)) {
            throw new \Exception('无权限处理');
        }
        if (TeamUserEntity::where('user_id', $user)->where('team_id', $id)
            ->count() > 0) {
            throw new \Exception('已处理过');
        }
        $userModel = UserRepository::basic($user);
        if (empty($userModel)) {
            ApplyRepository::receiveCancel($user, Constants::TYPE_TEAM, $id);
            throw new \Exception('用户不存在');
        }
        TeamUserEntity::create([
            'team_id' => $id,
            'user_id' => $userModel->id,
            'name' => $userModel->name,
            'role_id' => 0,
            'status' => 5,
        ]);
        ApplyRepository::receive($user, Constants::TYPE_TEAM, $id, ApplyRepository::STATUS_CONFIRM);
    }

    public static function apply(int $id, string $remark = '') {
        if (static::canable($id)) {
            throw new \Exception('你已加入该群');
        }
        if (TeamEntity::where('id', $id)->count() < 1) {
            throw new \Exception('群不存在');
        }
        ApplyRepository::receiveCreate(auth()->id(), Constants::TYPE_TEAM, $id, $remark);
    }

    public static function applyLog(int $id) {
        if (!static::manageable($id)) {
            throw new \Exception('无权限处理');
        }
        return ApplyRepository::receiveSearch(Constants::TYPE_TEAM, $id);
    }

    /**
     * 创建群
     * @param array $data
     */
    public static function create(array $data) {
        $model = new TeamEntity($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        TeamUserEntity::create([
            'user_id' => $model->user_id,
            'name' => auth()->user()->name,
            'team_id' => $model->id,
            'role_id' => 99,
            'status' => 5,
        ]);
        return $model;
    }

    /**
     * 解散群
     * @param int $id
     */
    public static function disband(int $id) {
        $model = TeamEntity::find($id);
        if ($model->user_id !== auth()->id()) {
            throw new \Exception('无权限操作');
        }
        $model->delete();
        TeamUserEntity::where('team_id', $model->id)->delete();
        event(new DisbandTeam($model->id, time()));
    }

    public static function at(string $content, int $team): array {
        if (empty($content) || !str_contains($content, '@')) {
            return [];
        }
        if (!preg_match_all('/@(\S+?)\s/', $content, $matches, PREG_SET_ORDER)) {
            return [];
        }
        $names = array_column($matches, 0, 1);
        $users = TeamUserEntity::whereIn('name', array_keys($names))->where('team_id', $team)->asArray()->get();
        if (empty($users)) {
            return [];
        }
        $rules = [];
        $currentUser = auth()->id();
        $userIds = [];
        foreach ($users as $user) {
            if ($user['user_id'] != $currentUser) {
                $userIds[] = $user['user_id'];
            }
            $rules[] = LinkRule::formatUser($names[$user['name']], intval($user['user_id']));
        }
        if (!empty($userIds)) {
            $group = TeamEntity::find($team);
            BulletinRepository::message($userIds,
                sprintf('我在群【%s】提到了你', $group->name), '[回复]', 88, [
                    LinkRule::formatLink('[回复]', 'chat')
                ]);
        }
        return $rules;
    }
}