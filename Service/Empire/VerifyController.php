<?php
namespace Service\Empire;

use Zodream\Domain\Image\VerifyCode;
use Zodream\Domain\Response\Image;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Session;

class VerifyController extends Controller {
	function indexAction() {
		$level = intval(Request::get('level'));
		if (empty($level)) {
			$level = Session::getValue('level');
		}
		$verify = new VerifyCode();
		Session::getValue('code', $verify->getCode());
		Image::show($verify->generate($level - 1));
	}
}