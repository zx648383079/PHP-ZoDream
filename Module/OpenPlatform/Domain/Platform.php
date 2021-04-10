<?php
declare(strict_types=1);
namespace Module\OpenPlatform\Domain;

use Module\Auth\Domain\Model\UserModel;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformOptionModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Database\Model\UserModel as UserObject;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Helpers\Arr;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Route\Response\RestResponse;

class Platform {

    /**
     * 全局的平台key
     */
    const PLATFORM_KEY = 'platform';


    private $app;

    private array $options = [];

    private Input $request;

    public function __construct($app) {
        $this->app = $app;
        $this->request = request();
    }

    public function id() {
        return $this->app['id'];
    }

    public function type() {
        return $this->app['type'];
    }

    /**
     * 获取设置
     * @param string $store
     * @param string $name
     * @return array|string
     */
    public function option(string $store, $name = null) {
        if (!isset($this->options[$store])) {
            $this->options[$store] = PlatformOptionModel::options($this->id(), $store);
        }
        if (empty($name)) {
            return $this->options[$store];
        }
        return $this->options[$store][$name] ?? '';
    }

    public function getCookieTokenKey(): string {
        return $this->app['appid'].'token';
    }

    public function sign(array $data): string {
        if ($this->app['sign_type'] < 1) {
            return '';
        }
        $content = $this->getSignContent($data);
        if ($this->app['sign_type'] == 1) {
            return md5($content);
        }
        return '';
    }

    protected function getSignContent(array $data): string {
        $data['appid'] = $this->app['appid'];
        $data['secret'] = $this->app['secret'];
        if (!str_contains($this->app['sign_key'], '+')) {
            ksort($data);
            return implode('', array_keys($data)).$this->app['sign_key'];
        }
        $args = [];
        foreach (explode('+', $this->app['sign_key']) as $key) {
            if (empty($key)) {
                $args[] = '+';
                continue;
            }
            if (isset($data[$key])) {
                $args[] = $data[$key];
                continue;
            }
            $args[] = $this->request->get($key);
        }
        return implode('', $args);
    }

    public function verify(array $data, $sign): bool {
        if ($this->app['sign_type'] < 1) {
            return true;
        }
        return $this->sign($data) === $sign;
    }

    public function encrypt(string $data): string {
        return '';
    }

    public function decrypt(string $data): array {
        return [];
    }

    public function ready(RestResponse $response): Output {
        $data = $response->getData();
        if (!Arr::isAssoc($data)) {
            $data = compact('data');
        }
        if ($this->app['encrypt_type'] > 0) {
            $data['encrypt'] = $this->encrypt($response->text());
            //$data['encrypt_type'] = $this->encrypt_type_list[$this->encrypt_type];
        }
        $data['appid'] = $this->app['appid'];
        $data['timestamp'] = date('Y-m-d H:i:s');
        if ($this->app['sign_type'] > 0) {
            //$data['sign_type'] = $this->sign_type_list[$this->sign_type];
            $data['sign'] = $this->sign($data);
        }
        return $response->setData($data);
    }

    public function verifyRest() {
        if (!$this->verify($_POST, $this->request->get('sign'))) {
            return false;
        }
        if ($this->app['encrypt_type'] < 1) {
            return true;
        }
        $data = $this->decrypt($this->request->get('encrypt'));
        if (empty($data)) {
            return false;
        }
        $this->request->append($data);
        return true;
    }

    public function verifyRule($module, $path) {
        if ($this->type() < 1 && !$this->verifyReferer()) {
            return false;
        }
        $rules = $this->app['rules'];
        if (empty($rules)) {
            return true;
        }
        $rules = explode("\n", $rules);
        foreach ($rules as $rule) {
            $rule = trim($rule);
            if (empty($rule)) {
                continue;
            }
            if (substr($rule, 0, 1) === '-') {
                if ($this->verifyOneRule(substr($rule, 1), $module, $path)) {
                    return false;
                }
                continue;
            }
            if ($this->verifyOneRule($rule, $module, $path)) {
                return true;
            }
        }
        return true;
    }

    private function verifyReferer(): bool {
        $url = $this->request->referrer();
        if (empty($url)) {
            return false;
        }
        return str_contains($url, $this->app['domain']);
    }

    private function verifyOneRule($rule, $module, $path): bool {
        if ($rule === '*') {
            return true;
        }
        $first = substr($rule, 0, 1);
        if ($first === '@') {
            return str_contains($module, substr($rule, 1));
        }
        if ($first === '^') {
            return preg_match('#'. $rule.'#i', $path, $match) === 1;
        }
        if ($first !== '~') {
            return $rule === $path;
        }
        return preg_match('#'.substr($rule, 1).'#i', $path, $match) === 1;
    }

    /**
     * 生成并保存token
     * @param UserObject $user
     * @return string
     * @throws \Exception
     */
    public function generateToken(UserObject $user) {
        $token = app(JWTAuth::class)->createToken($user);
        UserTokenModel::create([
            'user_id' => $user->getIdentity(),
            'platform_id' => $this->id(),
            'token' => $token,
            'expired_at' => time() + 20160
        ]);
        return $token;
    }

    /**
     * 使用临时生成的token
     * @throws \Exception
     */
    public function useCustomToken() {
        if ($this->app['allow_self'] < 1) {
            return;
        }
        $token = auth()->getToken();
        if (empty($token) || strlen($token) !== 32) {
            return;
        }
        $user_id = UserTokenModel::where('token', $token)
            ->where('platform_id', $this->id())
            ->where('expired_at', '>', time())->value('user_id');
        if (!$user_id || $user_id < 1) {
            return;
        }
        auth()->setUser(UserModel::findByIdentity($user_id));
    }

    /**
     * 验证token
     * @param $token
     * @return bool
     */
    public function verifyToken($token) {
        $count = UserTokenModel::where('platform_id', $this->id())
            ->where('token', $token)->where('expired_at', '>', time())->count();
        return $count > 0;
    }

    public function __sleep(): array
    {
        return ['app', 'options'];
    }

    public function __wakeup(): void
    {
        $this->request = request();
    }

    public static function create(string $appId) {
        $platform = PlatformModel::findByAppId($appId);
        if (empty($platform)) {
            throw new \Exception(__('APP ID error'));
        }
        return new static($platform);
    }

    public static function createAuto(string $key = 'appid') {
        return static::create(request()->get($key));
    }

    public static function createId(string|int $id) {
        $platform = PlatformModel::find($id);
        if (empty($platform)) {
            throw new \Exception(__('id error'));
        }
        return new static($platform);
    }

    /**
     * 全局获取平台id
     * @return int
     * @throws \Exception
     */
    public static function platformId() {
        return app()->has(static::PLATFORM_KEY) ? app(static::PLATFORM_KEY)->id() : 0;
    }

    /**
     * 进入场景
     * @param $platform
     * @throws \Exception
     */
    public static function enterPlatform($platform) {
        if (is_int($platform)) {
            $platform = static::createId($platform);
        } elseif (is_string($platform)) {
            $platform = static::create($platform);
        }
        app()->instance(static::PLATFORM_KEY, $platform);
    }
}