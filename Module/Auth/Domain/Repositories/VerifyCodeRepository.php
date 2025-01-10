<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Exception;
use Module\Auth\Domain\Helpers;
use Module\Auth\Domain\Model\UserModel;
use Module\MessageService\Domain\Repositories\MessageProtocol;
use Zodream\Helpers\Str;
use Zodream\Helpers\Time;
use Zodream\Validate\Validator;

final class VerifyCodeRepository {

    const MAX_DURATION = 600; // 验证码有效期

    /**
     * 发送验证码
     * @param array{to_type: string, to: string|null, event: string} $data
     * @return void
     */
    public static function sendCode(array $data): array {
        $data = self::filter($data);
        if (BanRepository::isBan($data['to'], $data['to_type'] === 'email' ?
            AuthRepository::ACCOUNT_TYPE_EMAIL : AuthRepository::ACCOUNT_TYPE_MOBILE)) {
            throw new Exception('已列入黑名单，禁止发送验证码');
        }
        $user = UserModel::where($data['to_type'], $data['to'])->first('name');
        if (in_array($data['event'], ['verify_new', 'register']) && !empty($user)) {
            throw new Exception('已存在');
        }
        $code = Str::randomByNumber();
        $nickname = $user ? $user->name : '用户';
        if ($data['to_type'] === 'email') {
            if (!static::sendMail($nickname, $data['to'], $code)) {
                throw new Exception('发送失败');
            }
            self::save($data['event'], $data['to'], $code);
            $data['to'] = Helpers::hideEmail($data['to']);
            return $data;
        }
        if (!static::sendSms($nickname, $data['to'], $code)) {
            throw new Exception('发送失败');
        }
        self::save($data['event'], $data['to'], $code);
        $data['to'] = Helpers::hideTel($data['to']);
        return $data;
    }

    /**
     * 验证验证码
     * @param array{to_type: string, to: string|null, code: string, event: string} $data
     * @param bool $refreshCode
     * @return bool
     * @throws Exception
     */
    public static function verifyCode(array $data, bool $refreshCode = false): bool {
        $data = self::filter($data);
        return self::verify($data['event'], $data['to'], $data['code'], $refreshCode);
    }

    private static function filter(array $data): array {
        if (!in_array($data['to_type'], ['email', 'mobile']) || !in_array($data['event'], [
                'verify_old',
                'verify_new',
                'login',
                'register'
            ])) {
            throw new Exception('check data error');
        }
        if ($data['event'] === 'verify_old') {
            if (auth()->guest()) {
                throw new Exception('请先登录');
            }
            $data['to'] = auth()->user()[$data['to_type']];
            if ($data['to_type'] === 'email') {
                if (AuthRepository::isEmptyEmail($data['to'])) {
                    throw new Exception('email error');
                }
            } else if (empty($data['to'])) {
                throw new Exception('未绑定手机号');
            }
            return $data;
        }
        if ($data['to_type'] === 'email' && !Validator::email()->validate($data['to'])) {
            throw new Exception('email is error');
        }
        if ($data['to_type'] === 'mobile' && !Validator::phone()->validate($data['to'])) {
            throw new Exception('mobile is error');
        }
        return $data;
    }

    public static function save(string $event, string $to, string $code) {
        $key = sprintf('%s_%s', $event, $to);
        cache()->set($key, $code, self::MAX_DURATION);
    }

    public static function verify(string $event, string $to, string $code, bool $remove = false): bool {
        $key = sprintf('%s_%s', $event, $to);
        $value = cache()->get($key);
        $res = $value === $code;
        if ($res && $remove) {
            cache()->delete($key);
        }
        return $res;
    }

    private static function sendSms(string $nickname, string $mobile, string $code): bool {
        return MessageProtocol::sendCode($mobile, MessageProtocol::EVENT_LOGIN_CODE, $code, [
            'name' => $nickname,
            'time' => Time::format(),
            'code' => $code,
        ]);
    }

    private static function sendMail(string $nickname, string $email, string $code): bool {
        return MessageProtocol::sendCode($email, MessageProtocol::EVENT_LOGIN_CODE, $code, [
            'name' => $nickname,
            'time' => Time::format(),
            'code' => $code,
        ]);
    }
}