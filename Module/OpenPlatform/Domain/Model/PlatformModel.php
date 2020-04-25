<?php
namespace Module\OpenPlatform\Domain\Model;

use Domain\Model\Model;
use Module\Auth\Domain\Model\UserModel;
use Zodream\Database\Model\UserModel as UserObject;
use Zodream\Helpers\Arr;
use Zodream\Infrastructure\Http\Output\RestResponse;

/**
 * Class PlatformModel
 * @package Module\OpenPlatform\Domain\Model
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property integer $type
 * @property string $domain
 * @property string $description
 * @property string $appid
 * @property string $secret
 * @property integer $sign_type
 * @property string $sign_key
 * @property integer $encrypt_type
 * @property string $public_key
 * @property string $rules
 * @property integer $status
 * @property integer $allow_self
 * @property integer $created_at
 * @property integer $updated_at
 */
class PlatformModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_WAITING = 9;
    const STATUS_SUCCESS = 1;

    public $type_list = [
        '网站',
        'APP',
        '小程序',
        '游戏',
    ];

    public $sign_type_list = [
        '不签名',
        'MD5',
        'HMAC',
    ];

    public $encrypt_type_list = [
        '不加密',
        'RSA',
        'RSA2',
        'DES'
    ];

    public static function tableName() {
        return 'open_platform';
    }


    protected function rules() {
        return [
            'user_id' => 'required|int',
            'name' => 'required|string:0,20',
            'type' => 'int:0,9',
            'domain' => 'required|string:0,50',
            'appid' => 'required|string:0,12',
            'secret' => 'required|string:0,32',
            'sign_type' => 'int:0,9',
            'sign_key' => 'string:0,32',
            'encrypt_type' => 'int:0,9',
            'public_key' => '',
            'rules' => '',
            'description' => '',
            'allow_self' => 'int',
            'status' => 'int:0,127',
            'created_at' => 'int',
            'updated_at' => 'int',
        ];
    }

    protected function labels() {
        return [
            'id' => 'Id',
            'user_id' => 'User Id',
            'name' => '名称',
            'type' => '类型',
            'domain' => '域名',
            'appid' => 'App Id',
            'secret' => 'Secret',
            'sign_type' => '签名方式',
            'sign_key' => '签名密钥',
            'encrypt_type' => '加密方式',
            'public_key' => '加密公钥',
            'rules' => '允许规则',
            'status' => '状态',
            'description' => '介绍',
            'allow_self' => '允许手动生成',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function generateNewId() {
        $this->appid = '1'.time();
        $this->secret = md5(uniqid(md5(microtime(true)),true));
    }

    public function getCookieTokenKey() {
        return $this->appid.'token';
    }

    public function sign(array $data) {
        if ($this->sign_type < 1) {
            return '';
        }
        $content = $this->getSignContent($data);
        if ($this->sign_type == 1) {
            return md5($content);
        }
        return '';
    }

    protected function getSignContent(array $data) {
        $data['appid'] = $this->appid;
        $data['secret'] = $this->secret;
        if (!strpos($this->sign_key, '+') > 0) {
            ksort($data);
            return implode('', array_keys($data)).$this->sign_key;
        }
        $args = [];
        foreach (explode('+', $this->sign_key) as $key) {
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
        if ($this->sign_type < 1) {
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
        if ($this->encrypt_type > 0) {
            $data['encrypt'] = $this->encrypt($response->text());
            //$data['encrypt_type'] = $this->encrypt_type_list[$this->encrypt_type];
        }
        $data['appid'] = $this->appid;
        $data['timestamp'] = date('Y-m-d H:i:s');
        if ($this->sign_type > 0) {
            //$data['sign_type'] = $this->sign_type_list[$this->sign_type];
            $data['sign'] = $this->sign($data);
        }
        return $response->setData($data);
    }

    public function verifyRest() {
        if (!$this->verify($_POST, app('request')->get('sign'))) {
            return false;
        }
        if ($this->encrypt_type < 1) {
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
            return substr($rule, 1) === $path;
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
        $token = auth()->createToken($user);
        UserTokenModel::create([
            'user_id' => $user->getIdentity(),
            'platform_id' => $this->id,
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
        if ($this->allow_self < 1) {
            return;
        }
        $token = auth()->getToken();
        if (empty($token) || strlen($token) !== 32) {
            return;
        }
        $user_id = UserTokenModel::where('token', $token)
            ->where('platform_id', $this->id)
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
        $count = static::where('platform_id', $this->id)
            ->where('token', $token)->where('expired_at', '>', time())->count();
        return $count > 0;
    }

    /**
     * @param $id
     * @return PlatformModel
     */
    public static function findByAppId($id) {
        return static::where('appid', $id)->first();
    }
}