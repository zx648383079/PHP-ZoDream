<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Module\Auth\Domain\Model\InviteCodeModel;
use Module\Auth\Domain\Model\InviteLogModel;
use Zodream\Helpers\Str;

class InviteRepository {

    public static function codeList(string $keywords = '', int $user = 0) {
        return InviteCodeModel::with('user')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('code', $keywords);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')->page();
    }

    public static function codeCreate(int $amount = 1, string|int $expiredAt = '') {
        do {
            $code = Str::randomNumber(6);
        } while (static::hasCode($code));
        return InviteCodeModel::createOrThrow([
            'user_id' => auth()->id(),
            'code' => $code,
            'amount' => $amount,
            'expired_at' => is_numeric($expiredAt) || empty($expiredAt) ? intval($expiredAt) : strtotime($expiredAt),
        ]);
    }

    private static function hasCode(string $code): bool {
        return InviteCodeModel::where('code', $code)
            ->where(function ($query) {
                $query->where('expired_at', '>', time())
                    ->orWhere('expired_at', 0);
            })->count() > 0;
    }

    public static function findCode(string $code): ?InviteCodeModel {
        return InviteCodeModel::where('code', $code)
            ->where(function ($query) {
                $query->where('expired_at', '>', time())
                    ->orWhere('expired_at', 0);
            })->first();
    }

    public static function codeRemove(int $id) {
        InviteCodeModel::where('id', $id)->delete();
    }

    public static function codeClear() {
        InviteCodeModel::query()->delete();
    }

    public static function logList(string $keywords = '', int $user = 0, int $inviter = 0) {
        return InviteLogModel::with('user', 'inviter')
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('code', $keywords);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->when($inviter > 0, function ($query) use ($inviter) {
                $query->where('parent_id', $inviter);
            })->orderBy('id', 'desc')->page();
    }
}