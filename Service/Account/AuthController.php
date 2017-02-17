<?php
namespace Service\Account;
/**
 * 登陆相关
 */
use Domain\Model\Auth\LoginLogModel;
use Domain\Model\Auth\UserModel;
use Zodream\Infrastructure\Http\Request;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Service\Factory;

class AuthController extends Controller {

	protected function rules() {
		return array(
			'logout' => '@',
			'check' => '*',
			'*' => '?'
		);
	}

	public function indexAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->redirect(Request::get('ReturnUrl', 'index.php'));
        }
		$time = TimeExpand::getBeginAndEndTime(TimeExpand::TODAY);
		$num = LoginLogModel::count(array(
			'ip' => Request::ip(),
			'status = 0',
			 array(
				 'create_at', 'between', $time[0], $time[1]
			)
		));
		if ($num > 2) {
			$num = intval($num / 3);
			$this->send('code', $num);
			Factory::session()->set('level', $num);
		}
		return $this->show(array(
			'title' => '用户登录'
		));
	}

	public function checkAction() {
	    list($name, $value) = Request::post('name,value');
	    if (!in_array($name, ['username', 'email'])) {
	        return $this->ajax([
	            'code' => 1,
                'msg' => '查询失败！'
            ]);
        }
        $count = UserModel::find()->where([$name => $value])->count('id')->scalar();
        return $this->ajax([
            'code' => 0,
            'data' => $count > 0
        ]);
    }

    public function loginAction() {
        $user = new UserModel();
        if ($user->load() && $user->signIn()) {
            return $this->ajax([
                'code' => 0
            ]);
        }
        return $this->ajax([
            'code' => 1,
            'msg' => '登录失败！',
            'errors' => $user->getError()
        ]);
    }


	public function registerAction() {
		return $this->show(array(
			'title' => '后台注册'
		));
	}

	public function findAction() {
		return $this->show([
			'title' => '找回密码'
		]);
	}

	/**
	 * @param Post $post
	 */
	public function findPost($post) {
		$email = $post->get('email');
		$result = DataFilter::validate($email, 'email');
		if (!$result) {
			$this->send('message', '您输入的邮箱有误！');
			return;
		}
		$user = EmpireModel::query('user')->findOne(['email' => $email]);
		if (empty($user)) {
			$this->send('message', '您输入的邮箱未注册！');
			return;
		}
		$time = time();
		$token = md5(StringExpand::random(10).$time);
		$template = new Template();
		$template->set([
			'time' => TimeExpand::format($time),
			'name' => $user['name'],
			'url' => Url::to(['auth/reset', 'token' => $token, 'email' => $email])
		]);

		$mailer = new Mailer();
		$mailer->isHtml(true);
		$mailer->addAddress($email, $user['name']);
		$result = $mailer->send('重置密码邮件', $template->getText('email.html'));
		if (!$result) {
			$this->send('message', $mailer->getError());
			return;
		}
		$result = EmpireModel::query('user_reset')->add([
			'user_id' => $user['id'],
			'ip' => Request::ip(),
			'token' => $token,
			'create_at' => $time,
			'email' => $email
		]);
		if (!$result) {
			$this->send('message', '发送失败，请重试！');
			return;
		}
		$this->send('message', '发送成功！');
 	}

	function resetAction($token, $email) {
		$model = EmpireModel::query('user_reset')->findOne([
			'token' => $token,
			'email' => $email,
			'create_at > '.(time()-86400)
		]);
		if (empty($model)) {
			Redirect::to('/', 2, '验证信息错误');
		}
		return $this->show([
			'title' => '重置密码',
			'token' => $token,
			'email' => $email
		]);
	}

	/**
	 * @param Request\Post $post
	 */
	function resetPost($post) {
		$token = $post->get('token');
		$email = $post->get('email');
		$model = EmpireModel::query('user_reset')->findOne([
			'token' => $token,
			'email' => $email,
			'create_at > '.(time()-86400)
		]);
		if (empty($model)) {
			Redirect::to('/', 2, '验证信息错误');
		}
		$data = $post->get('password,repassword');
		if (!DataFilter::validate($data, [
			'password' => 'required|confirm:repassword|string:3-30'
		])) {
			$this->send('message', DataFilter::getFirstError('password'));
			return;
		}
		$result = EmpireModel::query('user')->updateById($model['user_id'], [
			'password' => Hash::make($data['password'])
		]);
		EmpireModel::query()->addLog($model['user_id'].' 修改了密码', 'reset');
		if (!empty($result)) {
			Redirect::to('/', 2, '密码修改成功！');
		}
		$this->show([
			'title' => '重置密码',
			'token' => $token,
			'email' => $email
		]);
	}

	function logoutAction() {
		Auth::user()->logout();
		return $this->redirect('/');
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
			throw new \InvalidArgumentException($type.' 的第三方登录组件不存在！');
		}
		$class = 'Zodream\\Domain\\ThirdParty\\OAuth\\'.$maps[$type];
		return new $class;
	}

	function oauthAction($type = 'qq') {
		$oauth = $this->getOAuth($type);
		return $oauth->login();
	}
}