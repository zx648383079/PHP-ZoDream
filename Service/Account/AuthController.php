<?php
namespace Service\Account;
/**
 * 登陆相关
 */
use Domain\Model\LoginLogModel;
use Domain\Model\UserModel;
use Zodream\Domain\Access\Auth;
use Zodream\Domain\Filter\DataFilter;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Url\Url;
use Zodream\Domain\ThirdParty\OAuth\BaseOAuth;
use Zodream\Domain\ThirdParty\OAuth\QQ;
use Zodream\Infrastructure\Error\Error;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\Mailer\Mailer;
use Zodream\Infrastructure\ObjectExpand\Hash;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
use Zodream\Infrastructure\ObjectExpand\TimeExpand;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Template;

class AuthController extends Controller {

	protected function rules() {
		return array(
			'logout' => '@',
			'check' => '*',
			'*' => '?'
		);
	}

	function indexAction() {
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


	function registerAction() {
		return $this->show(array(
			'title' => '后台注册'
		));
	}

	function registerPost() {
		if (EmpireForm::start()->register()) {
			Redirect::to('/');
		}
	}

	function findAction() {
		return $this->show([
			'title' => '找回密码'
		]);
	}

	/**
	 * @param Post $post
	 */
	function findPost($post) {
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
			Error::out($type.' 的第三方登录组件不存在！', __FILE__, __LINE__);
		}
		$class = 'Zodream\\Domain\\ThirdParty\\OAuth\\'.$maps[$type];
		return new $class;
	}

	function oauthAction($type = 'qq') {
		$oauth = $this->getOAuth($type);
		return $oauth->login();
	}

	function qqAction() {
		$oauth = new QQ();
	}
}