<?php
namespace Service\Account;
use Domain\Model\UserModel;
use Psr\Log\InvalidArgumentException;
use Zodream\Domain\ThirdParty\OAuth\BaseOAuth;
use Zodream\Infrastructure\Database\Query\Record;
use Zodream\Infrastructure\ObjectExpand\StringExpand;

/**
 * Created by PhpStorm.
 * User: zx648
 * Date: 2016/10/25
 * Time: 19:19
 */
class OauthController extends Controller {

    protected function rules() {
        return array(
            '*' => '?'
        );
    }

    public function indexAction($type = 'qq') {
        return $this->redirect($this->getOAuth($type)->login());
    }

    /**
     * @param string $type
     * @return BaseOAuth
     */
    protected function getOAuth($type = 'qq') {
        static $maps = [
            'qq' => 'QQ',
            'alipay' => 'ALiPay',
            'baidu' => 'BaiDu',
            'taobao' => 'TaoBao',
            'weibo' => 'WeiBo',
            'wechat' => 'WeChat',
            'github' => 'GitHub'
        ];
        $type = strtolower($type);
        if (!array_key_exists($type, $maps)) {
            throw new InvalidArgumentException($type.' 的第三方登录组件不存在！');
        }
        $class = 'Zodream\\Domain\\ThirdParty\\OAuth\\'.$maps[$type];
        return new $class;
    }


    /**
     * @param BaseOAuth $oauth
     * @return array
     */
    protected function loginWithOauth($oauth) {
        if (!$oauth->callback()) {
            return [];
        }
        $user = UserModel::findByOpenId($oauth->identity, $oauth->getName());
        if ($user !== false) {
            return [];
        }
        if (!$oauth->getInfo()) {
            return [];
        }
        $user = new UserModel();
        $user->name = $oauth->username;
        $user->sex = $oauth->sex;
        $user->avatar = $oauth->avatar;
        $user->setPassword(StringExpand::random());
        if (!$user->save()) {
            return [];
        }
        $record = new Record();
        $record->setTable('user_oauth')->set([
            'user_id' => $user->id,
        ])->insert();
    }
}