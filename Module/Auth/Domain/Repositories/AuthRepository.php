<?php
namespace Module\Auth\Domain\Repositories;

use Exception;
use Module\Auth\Domain\Events\Login;
use Module\Auth\Domain\Events\Register;
use Module\Auth\Domain\Exception\AuthException;
use Module\Auth\Domain\Model\ActionLogModel;
use Module\Auth\Domain\Model\LoginLogModel;
use Module\Auth\Domain\Model\MailLogModel;
use Module\Auth\Domain\Model\OAuthModel;
use Module\Auth\Domain\Model\RBAC\UserRoleModel;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Helpers\Html;
use Zodream\Helpers\Str;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\Mailer\Mailer;
use Zodream\Validate\Validator;

class AuthRepository {

    const UNSET_PASSWORD = 'no_password';

    /**
     * 登录
     * @param $email
     * @param $password
     * @param bool $remember
     * @param bool $replaceToken 是否替换记住我的token
     * @return bool|mixed
     * @throws Exception
     */
    public static function login($email, $password, $remember = false, $replaceToken = true) {
        if (empty($email) || empty($password)) {
            throw AuthException::invalidLogin();
        }
        if (!Validator::email()->validate($email)) {
            throw AuthException::invalidLogin();
        }
        $user = UserModel::findByEmail($email);
        if (empty($user)) {
            throw AuthException::invalidLogin();
        }
        if (!$user->validatePassword($password)) {
            throw AuthException::invalidLogin();
        }
        if ($user->status != UserModel::STATUS_ACTIVE) {
            throw AuthException::disableAccount();
        }
        if ($remember) {
            if ($replaceToken) {
                $user->setRememberToken(Str::random(60));
            }
            return self::doLogin($user, $remember);
        }
        if (!$user->save()) {
            throw AuthException::invalidLogin();
        }
        return self::doLogin($user, $remember);
    }

    public static function loginMobile($mobile, $password, $remember = false, $replaceToken = true) {
        throw new Exception('暂不支持手机号登录');
    }

    public static function loginMobileCode($mobile, $code, $remember = false, $replaceToken = true) {
        throw new Exception('暂不支持手机号登录');
    }

    /**
     * @param $name
     * @param $email
     * @param $password
     * @param $confirmPassword
     * @param bool $agreement
     * @return bool|mixed
     * @throws Exception
     */
    public static function register(
        $name, $email, $password, $confirmPassword, $agreement = false) {
        if (!$agreement) {
            throw new Exception('必须同意相关协议');
        }
        $name = Html::text($name);
        if (empty($name)) {
            throw new Exception('请输入昵称');
        }
        if (empty($email) || !Validator::email()->validate($email)) {
            throw new Exception('请输入正确的邮箱');
        }
        if (!self::verifyPassword($password)) {
            throw new Exception('密码长度必须不小于6位');
        }
        if ($confirmPassword !== $password) {
            throw new Exception('两次密码不一致');
        }
        $user = self::createUser($email, $name, $password);
        event(new Register($user, app('request')->ip(), time()));
        return self::doLogin($user);
    }

    public static function registerMobile(
        $name, $mobile, $code, $password, $confirmPassword, $agreement = false) {
        throw new Exception('暂不支持手机号注册');
    }

    /**
     * @param $email
     * @param $name
     * @param $password
     * @return UserModel
     * @throws Exception
     */
    private static function createUser($email, $name, $password) {
        $count = UserModel::where('email', $email)->orWhere('name', $name)
            ->count();
        if ($count > 0) {
            throw new Exception('昵称或邮箱已存在');
        }
        $user = new UserModel(compact('name', 'email'));
        $user->setPassword($password);
        $user->created_at = time();
        $user->avatar = '/assets/images/avatar/'.Str::randomInt(0, 48).'.png';
        $user->sex = UserModel::SEX_FEMALE;
        if (!$user->save()) {
            throw new Exception($user->getFirstError());
        }
        return $user;
    }

    private static function doLogin(UserModel $user, $remember = false, $vendor = LoginLogModel::MODE_WEB) {
        $res = $user->login($remember);
        if (!$res) {
            return $res;
        }
        $request = app('request');
        event(new Login($user, $request->server('HTTP_USER_AGENT'), $request->ip(), time()));
        auth()->user()->logLogin(true, $vendor);
        return $res;
    }

    public static function logout($cancelRemember = false) {
        if (auth()->guest()) {
            return;
        }
        $user = auth()->user();
        $user->logout();
    }

    public static function oauth(
        $type, $openid, callable $infoCallback, $unionId = null, $platform_id = 0) {
        $user = OAuthModel::findUserWithUnion($openid, $unionId, $type, $platform_id);
        if (!empty($user)) {
            if ($user->status !== UserModel::STATUS_ACTIVE) {
                throw AuthException::disableAccount();
            }
            self::doLogin($user, false, $type);
            return $user;
        }
        list($nickname, $email, $sex, $avatar) = call_user_func($infoCallback);
        $name = empty($nickname) ? '用户_'.time() : $nickname;
        if (!auth()->guest()) {
            $user = auth()->user();
            self::successBindUser($type, $user, $nickname, $openid, $unionId, $platform_id);
            return $user;
        }
        if (!empty($email)
            && UserModel::validateEmail($email) ) {
            $email = null;
        }
        if (empty($email) || !Validator::email()->validate($email)) {
            $email = sprintf('%s_%s@zodream.cn', $type, $openid);
        }
        if (empty($avatar)) {
            $avatar = '/assets/images/avatar/'.Str::randomInt(0, 48).'.png';
        }
        $password = self::UNSET_PASSWORD;
        $user = UserModel::create(compact('name',
            'email', 'password', 'sex', 'avatar'));
        if (empty($user)) {
            throw new Exception('系统错误！');
        }
        self::successBindUser($type, $user, $nickname, $openid, $unionId, $platform_id);
        event(new Register($user, app('request')->ip(), time()));
        self::doLogin($user, false, $type);
        return $user;
    }

    private static function successBindUser($type, $user, $nickname, $openid, $unionId = null, $platform_id = 0) {
        OAuthModel::bindUser($user, $openid, $unionId, $type, $nickname, $platform_id);
    }

    public static function password(
        $oldPassword, $password, $confirmPassword) {
        if (empty($oldPassword) || empty($password) || empty($confirmPassword)) {
            throw AuthException::invalidPassword();
        }
        if (!self::verifyPassword($password)) {
            throw new Exception('密码长度必须不小于6位');
        }
        if ($confirmPassword !== $password) {
            throw new Exception('两次密码不一致');
        }
        /** @var UserModel $user */
        $user = auth()->user();
        if ($user->password !== self::UNSET_PASSWORD && !$user->validatePassword($oldPassword)) {
            throw new Exception('密码不正确！');
        }
        $user->setPassword($password);
        if (!$user->save()) {
            throw new Exception($user->getFirstError());
        }
        return true;
    }

    public static function sendSmsCode($mobile, $type = 'login') {
        throw new Exception('暂不支持手机注册');
    }

    /**
     * @param $code
     * @return MailLogModel
     * @throws Exception
     */
    public static function verifyEmailCode($code) {
        if (empty($code)) {
            throw new Exception('重置码错误');
        }
        $log = MailLogModel::where('type', MailLogModel::TYPE_FIND)
            ->where('code', $code)
            ->where('created_at', '>', time() - 1800)
            ->first();
        if (empty($log)) {
            throw new Exception('安全代码已过期或不存在');
        }
        return $log;
    }

    public static function sendEmail($email, $code) {
        if (empty($email) || !Validator::email()->validate($email)) {
            throw new Exception('请输入有效邮箱');
        }
        $user = UserModel::findByEmail($email);
        if (empty($user)) {
            throw new Exception('邮箱未注册');
        }
        $count = MailLogModel::where('type', MailLogModel::TYPE_FIND)
            ->where('user_id', $user->id)
            ->where('created_at', '>', time() - 120)
            ->count();
        if ($count > 0) {
            throw new Exception('发送过于频繁，请稍后再试');
        }
        $html = view()->render('@root/Template/mail', [
            'name' => $user->name,
            'time' => Time::format(),
            'code' => $code,
            'url' => url('./password', compact('code'), true,false)
        ]);
        $mail = new Mailer();
        $res = $mail->isHtml()
            ->addAddress($email, $user->name)
            ->send('密码重置邮件', $html);
        if (!$res) {
            throw new Exception($mail->getError());//'邮件发送失败');
        }
        MailLogModel::create([
            'ip' => app('request')->ip(),
            'user_id' => $user->id,
            'type' => MailLogModel::TYPE_FIND,
            'code' => $code,
            'amount' => 10,
            'created_at' => time(),
        ]);
        return $res;
    }

    public static function resetPassword($code, $password, $confirmPassword, $email) {
        if (empty($email) || !Validator::email()->validate($email)) {
            throw new Exception('请输入有效邮箱');
        }
        if (!self::verifyPassword($password)) {
            throw new Exception('密码长度必须不小于6位');
        }
        if ($confirmPassword !== $password) {
            throw new Exception('两次密码不一致');
        }
        $log = self::verifyEmailCode($code);
        $user = UserModel::find($log->user_id);
        if (empty($user) || $user->status != UserModel::STATUS_ACTIVE) {
            throw AuthException::disableAccount();
        }
        if ($user->email !== $email) {
            throw new Exception('邮箱或安全码不正确');
        }
        MailLogModel::where('user_id', $log->user_id)
            ->where('type', MailLogModel::TYPE_FIND)
            ->delete();
        $user->setPassword($password);
        if (!$user->save()) {
            throw new Exception($user->getFirstError());
        }
        ActionLogModel::addLog($user->id, 'password', '重置密码');
        return true;
    }

    public static function updateProfile(Request $request) {
        $user = auth()->user();
        foreach (['name', 'sex', 'birthday', 'avatar'] as $key) {
            if (!$request->has($key)) {
                continue;
            }
            $value = Html::text($request->get($key));
            if (empty($value)) {
                continue;
            }
            if ($key === 'name' && UserModel::where('name', $value)
                    ->where('id', '<>', $user->id)
                    ->count() > 0) {
                throw new Exception('昵称已被占用');
            }
            $user->{$key} = $value;
        }
        $user->save();
        return $user;
    }


    /**
     * 登录通过请求头输入账户密码
     * @return bool
     * @throws Exception
     */
    public static function loginByBasicAuthorization(): bool {
        list($email, $password) = app('request')->basicToken();
        try {
            return self::login($email, $password);
        } catch (Exception $ex) {
            return false;
        }
    }

    public static function verifyPassword($password) {
        return !empty($password) && mb_strlen($password) >= 6;
    }

    /**
     * 创建管理员账户
     * @param $email
     * @param $password
     * @throws Exception
     */
    public static function createAdmin($email, $password) {
        $user = self::createUser($email, 'admin', $password);
        UserRoleModel::query()->insert([
           'user_id' => $user->id,
            'role_id' => 1
        ]);
    }

    public static function updateOAuthData($type, $openid, $data, $unionId = null, $platform_id = 0) {
        $model = OAuthModel::where('vendor', $type)
            ->where('platform_id', $platform_id)
            ->where('identity', $openid)->first();
        if (!empty($model)) {
            $model->data = $data;
            return $model->save();
        }
        if (empty($unionId)) {
            return OAuthModel::create([
                'user_id' => 0,
                'nickname' => '',
                'vendor' => $type,
                'identity' => $openid,
                'unionid' => $unionId.'',
                'data' => $data,
                'platform_id' => $platform_id,
            ]);
        }
        $model = OAuthModel::findWithUnion($unionId, $type, $platform_id);
        if (empty($model)) {
            return OAuthModel::create([
                'user_id' => 0,
                'nickname' => '',
                'vendor' => $type,
                'identity' => $openid,
                'unionid' => $unionId.'',
                'data' => $data,
                'platform_id' => $platform_id,
            ]);
        }
        return OAuthModel::create([
            'user_id' => $model->user_id,
            'nickname' => $model->nickname,
            'vendor' => $type,
            'identity' => $openid,
            'unionid' => $unionId.'',
            'data' => $data,
            'platform_id' => $platform_id,
        ]);
    }
}