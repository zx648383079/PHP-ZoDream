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
            ->whereIn('user_id', [0, auth()->id()])->all();
        foreach ($data as $key => $item) {
            $data[$key] = $item->toArray();
            if (!isset($item['users'])) {
                $data[$key]['users'] = [];
            }
            if ($item['user_id'] < 1) {
                $data[$key]['users'][] = auth()->user();
            }
            $data[$key]['count'] = count($data[$key]['users']);
            $data[$key]['online_count'] = 0;
        }
        return $this->jsonSuccess($data);
    }

    public function searchAction($keywords = null) {
        $groups = FriendGroupModel::where('user_id', auth()->id())->pluck('id');
        $exclude = empty($groups) ? [] : FriendModel::whereIn('group_id', $groups)
            ->pluck('user_id');
        $exclude[] = auth()->id();
        $data = UserModel::when(!empty($keywords), function ($query) {
            FriendModel::search($query, 'name');
        })->whereNotIn('id', $exclude)->page();
        return $this->jsonSuccess($data);
    }

    public function messageAction($user) {
        $data = MessageModel::with('user', 'receive')->where(function($query) use ($user) {
            $query->where('user_id', $user)->where('receive_id', auth()->id());
        })->orWhere(function($query) use ($user) {
            $query->where('receive_id', $user)->where('user_id', auth()->id());
        })->page();
        return $this->jsonSuccess($data);
    }

    public function sendMessageAction($user, $content) {
        $data = MessageModel::create([
            'receive_id' => $user,
            'content' => $content,
            'user_id' => auth()->id()
        ]);
        return $this->jsonSuccess($data);
    }

    public function agreeAction($user, $name, $group = 0) {
        if ($group < 1) {
            $group = FriendGroupModel::where('user_id', 0)->value('id');
        }
        FriendModel::create([
            'name' => $name,
            'group_id' => $group,
            'user_id' => $user,
        ]);
        return $this->jsonSuccess();
    }

    public function applyAction($user, $group = 0, $remark = null) {
        if ($group < 1) {
            $group = FriendGroupModel::where('user_id', 0)->value('id');
        }
        if (!ApplyModel::canApply($user)) {
            return $this->jsonFailure('不能重复申请');
        }
        $model = ApplyModel::create([
            'group_id' => $group,
            'user_id' => $user,
            'remark' => $remark,
            'apply_user' => auth()->id(),
            'status' => 0,
        ]);
        return $this->jsonSuccess();
    }

    public function applyLogAction() {
        $data = ApplyModel::with('user')->where('user_id', auth()->id())->orderBy('status asc')
            ->orderBy('id desc')->limit(10)->get();
        foreach ($data as $key => $item) {
            /** @var $item ApplyModel */
            $data[$key] = $item->toArray();
            $data[$key]['user'] = $item->user;
        }
        return $this->jsonSuccess($data);
    }
}