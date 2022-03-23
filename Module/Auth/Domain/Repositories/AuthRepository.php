<?php
declare(strict_types=1);
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
use Module\SEO\Domain\Option;
use Module\SMS\Domain\Sms;
use Zodream\Helpers\Html;
use Zodream\Helpers\Str;
use Zodream\Helpers\Time;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Mailer\Mailer;
use Zodream\Validate\Validator;

class AuthRepository {

    const UNSET_PASSWORD = 'no_password';
    const OPTION_REGISTER_CODE = 'auth_register';

    /**
     * 注册方式
     * @return int {0:默认注册, 1:邀请码注册, 2:关闭注册}
     */
    public static function registerType(): int {
        return Option::value(static::OPTION_REGISTER_CODE);
    }

    public static function loginPreCheck($ip, $account, $captcha = '') {
        $today = strtotime(date('Y-m-d 00:00:00'));
        // 判断 ip 是否登录次数过多
        $count = LoginLogModel::where('ip', $ip)
            ->where('status', 0)
            ->where('created_at', '>=', $today)->count();
        if ($count > 20) {
            throw AuthException::ipDisallow();
        }
        if (empty($account)) {
            return;
        }
        // 判断 账号 是否登录失败过多
        $count = LoginLogModel::where('user', $account)
            ->where('status', 0)
            ->where('created_at', '>=', $today)->count();
        if ($count > 10) {
            throw AuthException::accountDisallow();
        }
        if ($count < 3) {
            return;
        }
        // 验证 验证码
        if (empty($captcha)) {
            throw AuthException::invalidCaptcha();
        }
    }

    /**
     * 登录
     * @param string $email
     * @param string $password
     * @param bool $remember
     * @param bool $replaceToken 是否替换记住我的token
     * @return bool|mixed
     * @throws Exception
     */
    public static function login(string $email, string $password, bool $remember = false, bool $replaceToken = true) {
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
        return static::loginUser($user, $remember, $replaceToken);
    }

    public static function loginMobile(string $mobile, string $password, bool $remember = false, bool $replaceToken = true) {
        if (empty($mobile) || empty($password)) {
            throw AuthException::invalidLogin();
        }
        if (!Validator::phone()->validate($mobile)) {
            throw AuthException::invalidLogin();
        }
        $user = UserModel::where('mobile', $mobile)->first();
        if (empty($user)) {
            throw AuthException::invalidLogin();
        }
        if (!$user->validatePassword($password)) {
            throw AuthException::invalidLogin();
        }
        return self::loginUser($user, $remember, $replaceToken);
    }

    public static function loginMobileCode(string $mobile, string $code, bool $remember = false, bool $replaceToken = true) {
        if (empty($mobile) || empty($code)) {
            throw AuthException::invalidLogin();
        }
        if (!Validator::phone()->validate($mobile)) {
            throw AuthException::invalidLogin();
        }
        $sms = new Sms();
        if (!$sms->verifyCode($mobile, $code)) {
            throw new Exception('验证码错误');
        }
        $user = UserModel::where('mobile', $mobile)->first();
        if (empty($user)) {
            return static::quickRegisterMobile($mobile, $remember);
        }
        return self::loginUser($user, $remember, $replaceToken);
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @param string $confirmPassword
     * @param bool $agreement
     * @return bool|mixed
     * @throws Exception
     */
    public static function register(
        string $name, string $email, string $password, string $confirmPassword, bool $agreement = false, string $inviteCode = '') {
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
        event(new Register($user, request()->ip(), time()));
        return self::doLogin($user);
    }

    public static function registerMobile(
        string $name, string $mobile, string $code, string $password, string $confirmPassword, bool $agreement = false, string $inviteCode = '') {
        if (!$agreement) {
            throw new Exception('必须同意相关协议');
        }
        $name = Html::text($name);
        if (empty($name)) {
            throw new Exception('请输入昵称');
        }
        if (empty($mobile) || empty($code)) {
            throw AuthException::invalidLogin();
        }
        if (!Validator::phone()->validate($mobile)) {
            throw AuthException::invalidLogin();
        }
        if (!self::verifyPassword($password)) {
            throw new Exception('密码长度必须不小于6位');
        }
        if ($confirmPassword !== $password) {
            throw new Exception('两次密码不一致');
        }
        $sms = new Sms();
        if (!$sms->verifyCode($mobile, $code)) {
            throw new Exception('验证码错误');
        }
        $user = self::createUser($mobile, $name, $password, [
            'email' => static::emptyEmail()
        ], 'mobile');
        event(new Register($user, request()->ip(), time()));
        return self::doLogin($user);
    }

    public static function emptyEmail(): string {
        return sprintf('zreno_%s@zodream.cn', \time());
    }

    public static function isEmptyEmail(string $email): bool {
        return empty($email) || preg_match('/^zreno_\d{11}@zodream\.cn$/', $email);
    }

    /**
     * @param string $username
     * @param string $name
     * @param string $password
     * @param array $extra
     * @param string $usernameType
     * @return UserModel
     * @throws Exception
     */
    private static function createUser(string $username, string $name, string $password,
                                       array $extra = [],
                                       string $usernameType = 'email') {
        $count = UserModel::where($usernameType, $username)->orWhere('name', $name)
            ->count();
        if ($count > 0) {
            throw new Exception($usernameType === 'email' ? '昵称或邮箱已存在' : '昵称或手机号已存在');
        }
        $data = array_merge([
            'avatar' => '/assets/images/avatar/'.Str::randomInt(0, 48).'.png',
            'sex' => UserModel::SEX_FEMALE
        ], $extra, [
           $usernameType => $username,
           'name' => $name
        ]);
        $user = new UserModel($data);
        if (empty($password)) {
            $user->password = self::UNSET_PASSWORD;

        } else {
            $user->setPassword($password);
        }
        $user->created_at = time();
        if (!$user->save()) {
            throw new Exception($user->getFirstError());
        }
        return $user;
    }

    private static function doLogin(UserModel $user, bool $remember = false, string $vendor = LoginLogModel::MODE_WEB) {
        $res = $user->login($remember);
        if (!$res) {
            return $res;
        }
        $request = request();
        event(new Login($user, $request->server('HTTP_USER_AGENT', ''), $request->ip(), time()));
        auth()->user()->logLogin(true, $vendor);
        return $res;
    }

    public static function logout(bool $cancelRemember = false) {
        if (auth()->guest()) {
            return;
        }
        $user = auth()->user();
        $user->logout();
    }

    public static function oauth(
        string $type, string $openid, callable $infoCallback, ?string $unionId = null, int $platform_id = 0) {
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
        if (static::registerType() > 1) {
            throw AuthException::disableRegister();
        }
        if (!empty($email)
            && UserModel::validateEmail($email) ) {
            $email = null;
        }
        if (empty($email) || !Validator::email()->validate($email)) {
            $email = static::emptyEmail();
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
        event(new Register($user, request()->ip(), time()));
        self::doLogin($user, false, $type);
        return $user;
    }

    private static function successBindUser(string $type, $user, string $nickname, string $openid, ?string $unionId = null, $platform_id = 0) {
        OAuthModel::bindUser($user, $openid, $unionId, $type, $nickname, $platform_id);
    }

    public static function password(
        string $oldPassword, string $password, string $confirmPassword) {
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
        if ($user->validatePassword($password)) {
            throw AuthException::samePassword();
        }
        $user->setPassword($password);
        if (!$user->save()) {
            throw new Exception($user->getFirstError());
        }
        return true;
    }

    public static function sendSmsCode(string $mobile, string $type = 'login') {
        if (empty($mobile) || !Validator::phone()->validate($mobile)) {
            throw new Exception('手机号不正确');
        }
        $sms = new Sms();
        if (!$sms->verifyIp() || !$sms->verifyCount() || !$sms->verifySpace()) {
            throw new Exception('验证码发送失败');
        }
        if (!$sms->sendCode($mobile)) {
            throw new Exception('验证码发送失败');
        }
    }

    /**
     * @param $code
     * @return MailLogModel
     * @throws Exception
     */
    public static function verifyEmailCode(string $code) {
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

    public static function sendEmail(string $email, string $code) {
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
            'ip' => request()->ip(),
            'user_id' => $user->id,
            'type' => MailLogModel::TYPE_FIND,
            'code' => $code,
            'amount' => 10,
            'created_at' => time(),
        ]);
        return $res;
    }

    public static function resetPassword(string $code, string $password, string $confirmPassword, string $email) {
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
        if ($user->validatePassword($password)) {
            throw AuthException::samePassword();
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
        list($email, $password) = request()->basicToken();
        try {
            return self::login($email, $password);
        } catch (Exception $ex) {
            return false;
        }
    }

    public static function verifyPassword(string $password) {
        return !empty($password) && mb_strlen($password) >= 6;
    }

    /**
     * 创建管理员账户
     * @param $email
     * @param $password
     * @throws Exception
     */
    public static function createAdmin(string $email, string $password) {
        $user = self::createUser($email, 'admin', $password);
        UserRoleModel::query()->insert([
           'user_id' => $user->id,
            'role_id' => 1
        ]);
    }

    public static function updateOAuthData(string $type, string $openid, ?string $data, ?string $unionId = null, int $platform_id = 0) {
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

    /**
     * @param $user
     * @param mixed $remember
     * @param mixed $replaceToken
     * @return bool|null
     * @throws Exception
     */
    private static function loginUser(UserModel $user, bool $remember = false, bool $replaceToken = true): ?bool {
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

    private static function quickRegisterMobile(string $mobile, bool $remember) {
        $user = self::createUser($mobile, sprintf('zre_%s', time()), '', [
            'email' => sprintf('%s@zodream.cn', $mobile)
        ], 'mobile');
        event(new Register($user, request()->ip(), time()));
        return self::doLogin($user);
    }

    public static function sendCodeByUser(string $name) {

    }

    public static function verifyCodeByUser(string $name, string $code) {

    }

    public static function sendCodeByFind(string $name, string $value) {

    }

    public static function verifyCodeByFind(string $name, string $value, string $code) {

    }
}