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
use Zodream\Infrastructure\Mailer\Mailer;
use Zodream\Validate\Validator;

class AuthRepository {

    const ACCOUNT_TYPE_NAME = 1;
    const ACCOUNT_TYPE_EMAIL = 2;
    const ACCOUNT_TYPE_MOBILE = 3;
    const ACCOUNT_TYPE_OAUTH_QQ = 11;
    const ACCOUNT_TYPE_OAUTH_WX = 12;
    const ACCOUNT_TYPE_OAUTH_WX_MINI = 13;
    const ACCOUNT_TYPE_OAUTH_WEIBO = 14;
    const ACCOUNT_TYPE_OAUTH_TAOBAO = 15;
    const ACCOUNT_TYPE_OAUTH_ALIPAY = 16;
    const ACCOUNT_TYPE_OAUTH_GITHUB = 21;
    const ACCOUNT_TYPE_OAUTH_GOOGLE = 21;
    const ACCOUNT_TYPE_ID_CARD = 98;
    const ACCOUNT_TYPE_IP = 99;

    const UNSET_PASSWORD = 'no_password';
    const OPTION_REGISTER_CODE = 'auth_register';
    const OPTION_OAUTH_CODE = 'auth_oauth';

    /**
     * 注册方式
     * @return int {0:默认注册, 1:邀请码注册, 2:关闭注册}
     */
    public static function registerType(): int {
        return intval(Option::value(static::OPTION_REGISTER_CODE));
    }

    public static function openOAuth(): bool {
        return Str::toBool(Option::value(static::OPTION_OAUTH_CODE));
    }

    public static function loginPreCheck(string $ip, string $account, string $captcha = '') {
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
        if (BanRepository::isBan($email, self::ACCOUNT_TYPE_EMAIL)) {
            throw AuthException::disableAccount();
        }
        $user = UserModel::findByEmail($email);
        if (!UserRepository::isActive($user)) {
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
        if (BanRepository::isBan($mobile, self::ACCOUNT_TYPE_MOBILE)) {
            throw AuthException::disableAccount();
        }
        $user = UserModel::where('mobile', $mobile)->first();
        if (!UserRepository::isActive($user)) {
            throw AuthException::invalidLogin();
        }
        if (!$user->validatePassword($password)) {
            throw AuthException::invalidLogin();
        }
        return self::loginUser($user, $remember, $replaceToken);
    }

    public static function loginMobileCode(string $mobile, string $code,
                                           bool $remember = false,
                                           bool $replaceToken = true) {
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
        if (BanRepository::isBan($mobile, self::ACCOUNT_TYPE_MOBILE)) {
            throw AuthException::disableAccount();
        }
        $user = UserModel::where('mobile', $mobile)->first();
        if (!UserRepository::isActive($user)) {
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
        string $name, string $email, string $password, string $confirmPassword,
        bool $agreement = false, string $inviteCode = '', array $extra = []) {
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
        if (BanRepository::isBan($email, self::ACCOUNT_TYPE_EMAIL)) {
            throw AuthException::disableAccount();
        }
        if (!self::verifyPassword($password)) {
            throw new Exception('密码长度必须不小于6位');
        }
        if ($confirmPassword !== $password) {
            throw new Exception('两次密码不一致');
        }
        $user = static::checkInviteCode($inviteCode, function ($parent_id) use ($email, $name, $password, $extra) {
            $extra['parent_id'] = $parent_id;
            return self::createUser($email, $name, $password, $extra);
        });
        event(new Register($user, request()->ip(), time()));
        return self::doLogin($user);
    }

    public static function registerMobile(
        string $name, string $mobile, string $code, string $password, string $confirmPassword,
        bool $agreement = false, string $inviteCode = '') {
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
        if (BanRepository::isBan($mobile, self::ACCOUNT_TYPE_MOBILE)) {
            throw AuthException::disableAccount();
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
        $user = static::checkInviteCode($inviteCode, function ($parent_id) use ($mobile, $name, $password) {
            return self::createUser($mobile, $name, $password, [
                'email' => static::emptyEmail(),
                'parent_id' => $parent_id
            ], 'mobile');
        });
        event(new Register($user, request()->ip(), time()));
        return self::doLogin($user);
    }

    public static function emptyEmail(): string {
        return sprintf('zreno_%s@zodream.cn', \time());
    }

    public static function isEmptyEmail(string $email): bool {
        return empty($email) || preg_match('/^zreno_\d{11}@zodream\.cn$/', $email);
    }


    protected static function checkInviteCode(string $inviteCode, callable $func): UserModel {
        $model = InviteRepository::findCode($inviteCode);
        if (empty($model) && static::registerType() === 1) {
            throw new Exception('必须输入邀请码');
        }
        if (empty($model)) {
            return call_user_func($func, 0);
        }
        $user = call_user_func($func, $model->user_id);
        InviteRepository::addLog($model, $user->id);
        return $user;
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
        if (BanRepository::isBanOAuth($openid, $unionId, $type, $platform_id)) {
            throw AuthException::disableAccount();
        }
        $user = OAuthModel::findUserWithUnion($openid, $unionId, $type, $platform_id);
        if (!empty($user)) {
            if (!UserRepository::isActive($user)) {
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

    /**
     * @param array{verify_type: string, verify: string, password: string, confirm_password: string} $data
     * @return bool
     * @throws Exception
     */
    public static function password(array $data) {
        if (empty($data['verify']) || empty($data['password']) || empty($data['confirm_password'])) {
            throw AuthException::invalidPassword();
        }
        if (!self::verifyPassword($data['password'])) {
            throw new Exception('密码长度必须不小于6位');
        }
        if ($data['confirm_password'] !== $data['password']) {
            throw new Exception('两次密码不一致');
        }
        /** @var UserModel $user */
        $user = auth()->user();
        if ($data['verify_type'] === 'password') {
            if ($user->password !== self::UNSET_PASSWORD && !$user->validatePassword($data['verify'])) {
                throw new Exception('密码不正确！');
            }
        } elseif ($data['verify_type'] === 'email') {
            if (!VerifyCodeRepository::verify('verify_old', $user->email, $data['verify'], true)) {
                throw new Exception('验证码不正确！');
            }
        } else if (!VerifyCodeRepository::verify('verify_old', $user->mobile, $data['verify'], true)) {
            throw new Exception('验证码不正确！');
        }
        if ($user->validatePassword($data['password'])) {
            throw AuthException::samePassword();
        }
        $user->setPassword($data['password']);
        if (!$user->save()) {
            throw new Exception($user->getFirstError());
        }
        return true;
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
        if (BanRepository::isBan($email, self::ACCOUNT_TYPE_EMAIL)) {
            throw new Exception('邮箱已列入黑名单');
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
        if (!UserRepository::isActive($user)) {
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

    public static function updateProfile(array $data) {
        $user = auth()->user();
        foreach (['name', 'sex', 'birthday', 'avatar'] as $key) {
            if (!isset($data[$key])) {
                continue;
            }
            $value = Html::text($data[$key]);
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
     * @param string $email
     * @param string $password
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
                'unionid' => $unionId,
                'data' => $data,
                'platform_id' => $platform_id,
            ]);
        }
        return OAuthModel::create([
            'user_id' => $model->user_id,
            'nickname' => $model->nickname,
            'vendor' => $type,
            'identity' => $openid,
            'unionid' => $unionId,
            'data' => $data,
            'platform_id' => $platform_id,
        ]);
    }

    public static function loginUserId(int $id, string $vendor = LoginLogModel::MODE_WEB): void {
        static::loginUser(UserModel::findIdentity($id), vendor: $vendor);
    }

    /**
     * @param UserModel $user
     * @param mixed $remember
     * @param mixed $replaceToken
     * @param string $vendor
     * @return bool|null
     * @throws Exception
     */
    private static function loginUser(UserModel $user,
                                      bool $remember = false,
                                      bool $replaceToken = true, string $vendor = LoginLogModel::MODE_WEB): ?bool {
        if (!UserRepository::isActive($user)) {
            throw AuthException::disableAccount();
        }
        if ($remember) {
            if ($replaceToken) {
                $user->setRememberToken(Str::random(60));
            }
            return self::doLogin($user, $remember, $vendor);
        }
        if (!$user->save()) {
            throw AuthException::invalidLogin();
        }
        return self::doLogin($user, $remember, $vendor);
    }

    private static function quickRegisterMobile(string $mobile, bool $remember) {
        $user = self::createUser($mobile, sprintf('zre_%s', time()), '', [
            'email' => static::emptyEmail()
        ], 'mobile');
        event(new Register($user, request()->ip(), time()));
        return self::doLogin($user);
    }

    /**
     * @param array{verify_type: string, verify: string, name: string, value: string, code: string} $data
     * @return array
     * @throws Exception
     */
    public static function updateAccount(array $data): array {
        if (!in_array($data['verify_type'], ['email', 'mobile']) ||
            !in_array($data['name'], ['email', 'mobile'])) {
            throw new Exception('数据错误');
        }
        if (UserModel::where($data['name'], $data['value'])->count() > 0) {
            throw new Exception('账户已被使用');
        }
        /** @var UserModel $user */
        $user = auth()->user();
        if ($data['verify_type'] === 'email') {
            if (!VerifyCodeRepository::verify('verify_old', $user->email, $data['verify'], true)) {
                throw new Exception('验证码不正确！');
            }
        } else if (!VerifyCodeRepository::verify('verify_old', $user->mobile, $data['verify'], true)) {
            throw new Exception('验证码不正确！');
        }
        if (!VerifyCodeRepository::verify('verify_new', $data['value'],  $data['code'],true)) {
            throw new Exception('验证码不正确！');
        }
        $user->{$data['name']} = $data['value'];
        $user->save();
        return $user->toArray();
    }

    /**
     * 生成一次性凭证
     * @param int $duration 有效期 0 表示一次性
     * @return string
     */
    public static function ticket(int $duration = 0): string {
        if (auth()->guest()) {
            return '';
        }
        $userId = auth()->id();
        $ticket = md5(sprintf('%s_%d', $userId, Time::millisecond()));
        $store = cache()->store('auth');
        $store->set($ticket, [
            'user' => $userId,
            'duration' => $duration
        ], $duration <= 600 ? 8 * 3600 : $duration);
        return $ticket;
    }

    /**
     * 验证一次凭证
     * @param string $ticket
     * @param bool $autoLogin 是否进行登录
     * @return int 返回用户id
     * @throws Exception
     */
    public static function verifyTicket(string $ticket, bool $autoLogin = false): int {
        $store = cache()->store('auth');
        $data = $store->get($ticket);
        if (empty($data)) {
            return 0;
        }
        $userId = intval($data['user']);
        if ($data['duration'] <= 0) {
            $store->delete($ticket);
        }
        if ($userId <= 0 || !$autoLogin) {
            return $userId;
        }
        static::loginUserId($userId, LoginLogModel::MODE_TICKET);
        return $userId;
    }
}