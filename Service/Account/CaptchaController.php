<?php
namespace Service\Account;
/**
 * 验证码
 */
use Zodream\Image\Captcha;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;

class CaptchaController extends Controller {
	protected function rules() {
		return array(
			'*' => '*'
		);
	}
	
	function indexAction() {
		$level = intval(Request::get('level'));
		if (empty($level)) {
			$level = Factory::session('level');
		}
		$captcha = new Captcha();
		$captcha->setConfigs([
            'width' => 200,
            'fontSize' => 20,
            'fontFamily' => 'D:\Documents\Company\xtime\data\fonts\Ubuntu_regular.ttf'
        ]);
		return Factory::response()->image($captcha->generate($level));
	}
}