<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Model\Model;
use Module\Auth\Domain\Entities\InviteCodeEntity;
use Module\Auth\Domain\Entities\InviteLogEntity;
use Module\Auth\Domain\Model\InviteCodeModel;
use Module\Auth\Domain\Model\InviteLogModel;
use Zodream\Helpers\Str;

class InviteRepository {

    const TYPE_CODE = 0; // 邀请码
    const TYPE_LOGIN = 5; // 扫码登录

    const STATUS_UN_SCAN = 0;  //未扫码
    const STATUS_UN_CONFIRM = 1;  // 已扫码待确认
    const STATUS_SUCCESS = 2;     // 登录成功
    const STATUS_REJECT = 3;      // 拒绝登录

    public static function codeList(string $keywords = '', int $user = 0) {
        return InviteCodeModel::with('user')
            ->where('type', self::TYPE_CODE)
            ->when(!empty($keywords), function ($query) use ($keywords) {
                $query->where('code', $keywords);
            })->when($user > 0, function ($query) use ($user) {
                $query->where('user_id', $user);
            })->orderBy('id', 'desc')->page();
    }

    public static function codeCreate(int $amount = 1, string|int $expiredAt = '') {
        return [
            'code' => self::createNew(self::TYPE_CODE, $amount, $expiredAt)
        ];
    }

    private static function hasCode(string $code): bool {
        return InviteCodeEntity::where('token', $code)
            ->where(function ($query) {
                $query->where('expired_at', '>', time())
                    ->orWhere('expired_at', 0);
            })->count() > 0;
    }

    /**
     * 判断邀请码是否有效
     * @param string $code
     * @return InviteCodeModel|null
     */
    public static function findCode(string $code): ?InviteCodeModel {
        if (empty($code)) {
            return null;
        }
        /** @var InviteCodeModel $model */
        $model = InviteCodeModel::where('token', $code)
            ->where(function ($query) {
                $query->where('expired_at', '>', time())
                    ->orWhere('expired_at', 0);
            })->first();
        if (empty($model) || $model->amount > 0 && $model->invite_count == $model->amount) {
            return null;
        }
        return $model;
    }

    public static function codeRemove(int $id) {
        InviteCodeEntity::where('id', $id)->where('type', self::TYPE_CODE)->delete();
    }

    public static function codeClear() {
        InviteCodeEntity::query()->where('type', self::TYPE_CODE)->delete();
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

    public static function addLog(InviteCodeEntity $model, mixed $userId) {
        $model->invite_count ++;
        if ($model->amount > 0 && $model->invite_count == $model->amount) {
            $model->expired_at = \time() - 1;
        }
        $model->save();
        InviteLogEntity::create([
            'user_id' => $userId,
            'parent_id' => $model->user_id,
            'code_id' => $model->id,
            'status' => self::STATUS_SUCCESS,
        ]);
    }

    /**
     * 生成一个邀请码
     * @param int $type
     * @param int $amount
     * @param string|int $expiredAt
     * @return string
     * @throws \Exception
     */
    public static function createNew(int $type, int $amount = 1, string|int $expiredAt = ''): string {
        do {
            $code = self::generateCode($type);
        } while (static::hasCode($code));
        $at = is_numeric($expiredAt) || empty($expiredAt) ? intval($expiredAt) : strtotime($expiredAt);
        InviteCodeEntity::createOrThrow([
            'user_id' => auth()->id(),
            'type' => $type,
            'token' => $code,
            'amount' => $amount,
            'expired_at' => $at === 0 || strlen((string)$at) > 9 ? $at : (time() + $at),
        ]);
        return $code;
    }

    /**
     * 手动失效
     * @param int $type
     * @param string $token
     * @return void
     * @throws \Exception
     */
    public static function cancel(int $type, string $token): void {
        $model = InviteCodeEntity::where('type', $type)->where('token', $token)
            ->first();
        if (empty($model)) {
            return;
        }
        if ($model->user_id !== auth()->id()) {
            throw new \Exception('无权限操作');
        }
        $model->expired_at = \time() - 1;
        $model->save();
    }

    private static function generateCode(int $type): string {
        if ($type === self::TYPE_CODE) {
            return Str::randomNumber(6);
        }
        return md5(Str::randomBytes(20).time());
    }

    public static function loginQr(string $token): string {
        return url('./qr/authorize', ['token' => $token], true, false);
    }

    /**
     * 发起人验证
     * @param int $type
     * @param string $token
     * @return array 返回确认的用户id
     * @throws \Exception
     */
    public static function checkQr(int $type, string $token): array {
        $model = InviteCodeEntity::where('type', $type)->where('token', $token)
            ->first();
        if (empty($model)) {
            throw new \Exception('USER_TIPS_QR_OVERTIME', 204);
        }
        if ($model->invite_count < 1) {
            if (self::isExpired($model)) {
                throw new \Exception('USER_TIPS_QR_OVERTIME', 204);
            }
            throw new \Exception('QR_UN_SCANNED', 201);
        }
        if ($model->amount > 1) {
            return InviteLogEntity::where('code_id', $model->id)
                ->where('status', self::STATUS_SUCCESS)->pluck('user_id');
        }
        $log = InviteLogEntity::where('code_id', $model->id)
            ->first();
        if ($log->status == self::STATUS_UN_CONFIRM) {
            throw new \Exception('QR_UN_CONFIRM', 202);
        }
        if ($log->status != self::STATUS_REJECT) {
            throw new \Exception('QR_REJECT', 203);
        }
        if ($log->status != self::STATUS_SUCCESS) {
            return [];
        }
        return [$log['user_id']];
    }

    private static function isExpired(mixed $model): bool {
        $at = intval(is_array($model) || $model instanceof Model ? $model['expired_at'] : $model);
        return $at > 0 && $at <= time();
    }

    /**
     * 获取已授权的用户id
     * @param int $type
     * @param string $token
     * @return array
     */
    public static function authorizedUser(int $type, string $token): array {
        $id = InviteCodeEntity::where('type', $type)->where('token', $token)
            ->value('id');
        if (empty($id)) {
            return [];
        }
        return InviteLogEntity::where('code_id', $id)
            ->where('status', self::STATUS_SUCCESS)->pluck('user_id');
    }
    /**
     * 接受人授权
     * @param int $type
     * @param string $token
     * @param bool $confirm
     * @param bool $reject
     * @return ?bool
     */
    public static function authorize(int $type, string $token, bool $confirm = false,
                                     bool $reject = false): ?bool {
        if (auth()->guest()) {
            throw new \Exception('Need Login first', 204);
        }
        $model = InviteCodeEntity::where('type', $type)->where('token', $token)
            ->first();
        if (empty($model)) {
            throw new \Exception('USER_TIPS_QR_OVERTIME', 204);
        }
        if (self::isExpired($model)) {
            throw new \Exception('USER_TIPS_QR_OVERTIME', 204);
        }
        $userId = auth()->id();
        $log = InviteLogEntity::where('user_id', $userId)
            ->where('code_id', $model->id)->first();
        if (empty($log)) {
            if ($model->invite_count >= $model->amount) {
                throw new \Exception('USER_TIPS_QR_OVERTIME', 204);
            }
            $model->invite_count ++;
            $model->save();
            $log = InviteLogEntity::createOrThrow([
                'user_id' => $userId,
                'parent_id' => $model->user_id,
                'code_id' => $model->id,
                'status' => self::STATUS_UN_CONFIRM,
            ]);
        }
        if ($log->status != self::STATUS_UN_CONFIRM) {
            throw new \Exception('二维码已失效');
        }
        if (!empty($confirm)) {
            $log->status = self::STATUS_SUCCESS;
            $model->save();
            return true;
        }
        if (!empty($reject)) {
            $model->status = self::STATUS_REJECT;
            $model->save();
            return false;
        }
        return null;
    }
}