<?php
namespace Module\Chat\Service;

use Module\Auth\Domain\Model\UserModel;
use Module\Chat\Domain\Model\ApplyModel;
use Module\Chat\Domain\Model\FriendGroupModel;
use Module\Chat\Domain\Model\FriendModel;
use Module\Chat\Domain\Model\MessageModel;

class FriendController extends Controller {

    public function indexAction() {
        $data = FriendGroupModel::with('users')
            ->where('user_id', auth()->id())->orderBy('id asc')->all();
        if (empty($data)) {
            $data = [FriendGroupModel::create([
                'name' => '我的好友',
                'user_id' => auth()->id(),
            ])];
        }
        foreach ($data as $key => $item) {
            $data[$key] = $item->toArray();
            $data[$key]['users'] = $item['users'];
            if (!$item->relationLoaded('users')) {
                $data[$key]['users'] = [];
            }
            if ($key < 1) {
                $user = auth()->user();
                array_unshift($data[$key]['users'], new FriendModel([
                    'name' => $user->name,
                    'user' => $user
                ]));
            }
            $data[$key]['count'] = count($data[$key]['users']);
            $data[$key]['online_count'] = 0;
        }
        return $this->renderData($data);
    }

    public function searchAction($keywords = null) {
        $groups = FriendGroupModel::where('user_id', auth()->id())->pluck('id');
        $exclude = empty($groups) ? [] : FriendModel::whereIn('group_id', $groups)
            ->pluck('user_id');
        $exclude[] = auth()->id();
        $data = UserModel::when(!empty($keywords), function ($query) {
            FriendModel::searchWhere($query, 'name');
        })->whereNotIn('id', $exclude)->page();
        return $this->renderData($data);
    }

    public function messageAction($user) {
        $data = MessageModel::with('user', 'receive')->where(function($query) use ($user) {
            $query->where('user_id', $user)->where('receive_id', auth()->id());
        })->orWhere(function($query) use ($user) {
            $query->where('receive_id', $user)->where('user_id', auth()->id());
        })->page();
        return $this->renderData($data);
    }

    public function sendMessageAction($user, $content) {
        $data = MessageModel::create([
            'receive_id' => $user,
            'content' => $content,
            'user_id' => auth()->id()
        ]);
        return $this->renderData($data);
    }

    public function agreeAction($user, $name, $group = 0) {
        if ($group < 1) {
            $group = FriendGroupModel::where('user_id', auth()->id())->orderBy('id asc')->value('id');
        }
        $apply = ApplyModel::where('user_id', auth()->id())
            ->where('apply_user', $user)->where('status', 0)
            ->orderBy('id', 'desc')->first();
        if (empty($apply)) {
            return $this->renderFailure('无申请记录');
        }
        ApplyModel::where('user_id', auth()->id())
            ->where('apply_user', $user)->where('status', 0)->update([
                'status' => 1,
                'updated_at' => time()
            ]);
        FriendModel::create([
            'name' => $name,
            'group_id' => $group,
            'user_id' => $user,
            'belong_id' => auth()->id()
        ]);
        FriendModel::create([
            'name' => auth()->user()->name,
            'group_id' => $apply->group_id,
            'user_id' => $apply->user_id,
            'belong_id' => $user
        ]);
        return $this->renderData();
    }

    public function applyAction($user, $group = 0, $remark = null) {
        if ($group < 1) {
            $group = FriendGroupModel::where('user_id', auth()->id())->orderBy('id asc')->value('id');
        }
        if (!ApplyModel::canApply($user)) {
            return $this->renderFailure('不能重复申请');
        }
        $model = ApplyModel::create([
            'group_id' => $group,
            'user_id' => $user,
            'remark' => $remark,
            'apply_user' => auth()->id(),
            'status' => 0,
        ]);
        return $this->renderData();
    }

    public function applyLogAction() {
        $data = ApplyModel::with('applier', 'user')
            ->where('user_id', auth()->id())->orderBy('status asc')
            ->orderBy('id desc')->limit(10)->get();
        foreach ($data as $key => $item) {
            /** @var $item ApplyModel */
            $data[$key] = $item->toArray();
            $data[$key]['user'] = $item->user;
            $data[$key]['applier'] = $item->applier;
        }
        return $this->renderData($data);
    }

    public function deleteAction($user) {
        FriendModel::where('user_id', $user)->where('belong_id', auth()->id())->delete();
        FriendModel::where('belong_id', $user)->where('user_id', auth()->id())->delete();
        return $this->renderData();
    }

    public function moveAction($user, $group) {
        FriendModel::where('user_id', $user)->where('belong_id', auth()->id())->update([
            'group_id' => $group
        ]);
        return $this->renderData();
    }
}