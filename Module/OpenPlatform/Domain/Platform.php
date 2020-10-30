<?php
namespace Module\OpenPlatform\Domain;

use Module\Auth\Domain\Model\UserModel;
use Module\OpenPlatform\Domain\Model\PlatformModel;
use Module\OpenPlatform\Domain\Model\PlatformOptionModel;
use Module\OpenPlatform\Domain\Model\UserTokenModel;
use Zodream\Database\Model\UserModel as UserObject;
use Zodream\Domain\Access\JWTAuth;
use Zodream\Helpers\Arr;
use Zodream\Infrastructure\Http\Output\RestResponse;

class Platform {

    /**
     * 全局的平台key
     */
    const PLATFORM_KEY = 'platform';


    private $app;

    private $options = [];

    public function __construct($app) {
        $this->app = $app;
    }

    public function id() {
        return $this->app['id'];
    }

    /**
     * 获取设置
     * @param string $store
     * @param string $name
     * @return array|string
     */
    public function option($store, $name = null) {
        if (!isset($this->options[$store])) {
            $this->options[$store] = PlatformOptionModel::options($this->id(), $store);
        }
        if (empty($name)) {
            return $this->options[$store];
        }
        return isset($this->options[$store][$name]) ? $this->options[$store][$name] : '';
    }

    public function getCookieTokenKey() {
        return $this->app['appid'].'token';
    }

    public function sign(array $data) {
        if ($this->app['sign_type'] < 1) {
            return '';
        }
        $content = $this->getSignContent($data);
        if ($this->app['sign_type'] == 1) {
            return md5($content);
        }
        return '';
    }

    protected function getSignContent(array $data) {
        $data['appid'] = $this->app['appid'];
        $data['secret'] = $this->app['secret'];
        if (strpos($this->app['sign_key'], '+') === false) {
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
            $args[] = app('request')->get($key);
        }
        return implode('', $args);
    }

    public function verify(array $data, $sign) {
        if ($this->app['sign_type'] < 1) {
            return true;
        }
        return $this->sign($data) == $sign;
    }

    public function encrypt($data) {
        return '';
    }

    public function decrypt($data) {
        return [];
    }

    public function ready(RestResponse $response) {
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
        if (!$this->verify($_POST, app('request')->get('sign'))) {
            return false;
        }
        if ($this->app['encrypt_type'] < 1) {
            return true;
        }
        $data = $this->decrypt(app('request')->get('encrypt'));
        if (empty($data)) {
            return false;
        }
        app('request')->append($data);
        return true;
    }

    public function verifyRule($module, $path) {
        if (empty($this->rules)) {
            return true;
        }
        $rules = explode("\n", $this->rules);
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

    private function verifyOneRule($rule, $module, $path) {
        if ($rule === '*') {
            return true;
        }
        $first = substr($rule, 0, 1);
        if ($first === '@') {
            return strpos($module, substr($rule, 1)) !== false;
        }
        if ($first === '^') {
            return preg_match('#'. $rule.'#i', $path, $match);
        }
        if ($first !== '~') {
            return $rule === $path;
        }
        return preg_match('#'.substr($rule, 1).'#i', $path, $match);
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

    public static function create($appId) {
        $platform = PlatformModel::findByAppId($appId);
        if (empty($platform)) {
            throw new \Exception(__('APP ID error'));
        }
        return new static($platform);
    }

    public static function createAuto($key = 'appid') {
        return static::create(app('request')->get($key));
    }

    public static function createId($id) {
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