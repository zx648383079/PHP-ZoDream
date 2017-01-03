<?php
namespace Service\Account;
/**
 * 验证码
 */
use Zodream\Domain\Image\VerifyCode;
use Zodream\Domain\Response\ImageResponse;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\Http\Request;

class VerifyController extends Controller {
	protected function rules() {
		return array(
			'*' => '*'
		);
	}
	
	function indexAction() {
		$level = intval(Request::get('level'));
		if (empty($level)) {
			$level = Factory::session()->get('level');
		}
		$verify = new VerifyCode();
		Factory::session()->get('code', $verify->getCode());
		return new ImageResponse($verify->generate($level - 1));
	}
}