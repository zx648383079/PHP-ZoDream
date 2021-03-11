<?php
declare(strict_types=1);
namespace Module\OnlineService\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\Auth\Domain\Repositories\UserRepository;
use Module\OnlineService\Domain\Models\SessionLogModel;
use Module\OnlineService\Domain\Models\SessionModel;

class SessionRepository {
    public static function getList(string $keywords = '', int $status = 0) {
        return SessionModel::with('user')->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'ip']);
        })->when($status > 0, function ($query) use ($status) {
            $query->where('status', $status - 1);
        })->orderBy('id', 'desc')->page();
    }

    public static function myList() {
        $data = SessionModel::with('user')->where(function ($query) {
            $query->where(function ($query) {
                $query->where('status', 0)
                    ->where('updated_at', '>', time() - 3600);
            })->orWhere(function ($query) {
                $query->where('status', '>', 0)
                    ->where('service_id', auth()->id())
                    ->where('updated_at', '>', time() - 86400);
            });
        })->orderBy('id', 'desc')->get();
        foreach ($data as $item) {
            if (!$item->user) {
                $item->user = [
                    'name' => '游客',
                    'avatar' => url()->asset('assets/images/avatar/0.png')
                ];
            }
        }
        return $data;
    }

    public static function remark(int $sessionId, string $remark) {
        $session = SessionModel::findOrThrow($sessionId, '会话错误');
        $session->remark = $remark;
        $session->save();
        SessionLogModel::create([
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'remark' => sprintf('客服 【%s】 修改了备注：%s', auth()->user()->name, $remark),
            'status' => $session->status,
        ]);
        return $session;
    }

    public static function transfer(int $sessionId, int $user) {
        $session = SessionModel::findOrThrow($sessionId, '会话错误');
        if (!CategoryRepository::hasService($user)) {
            throw new \Exception('客服错误');
        }
        $session->service_id = $user;
        $session->service_word = 0;
        $session->save();
        SessionLogModel::create([
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'remark' => sprintf('客服 【%s】 转交了会话给客服【%s】', auth()->user()->name,
                UserRepository::getName($user)
            ),
            'status' => $session->status,
        ]);
        return $session;
    }

    /**
     * 设置自动回复
     * @param int $sessionId
     * @param int $user
     * @throws \Exception
     */
    public static function reply(int $sessionId, int $word) {
        $session = SessionModel::findOrThrow($sessionId, '会话错误');
        if ($word > 0 && !CategoryRepository::hasWord($word)) {
            throw new \Exception('自动回复语错误');
        }
        $session->service_word = $word;
        $session->save();
        return $session;
    }

    /**
     * 判断客服是否有权限查看
     * @param int $sessionId
     * @return bool
     * @throws \Exception
     */
    public static function hasRole(int $sessionId): bool {
        $session = SessionModel::find($sessionId);
        if (!$session) {
            return false;
        }
        if ($session->service_id < 1) {
            return true;
        }
        return $session->service_id === auth()->id();
    }

}