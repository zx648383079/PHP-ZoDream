<?php
namespace Service\Empire;

use Zodream\Domain\Image\VerifyCode;
use Zodream\Domain\Response\Image;
use Zodream\Infrastructure\Session;

class VerifyController extends Controller {
	function indexAction() {
		$verify = new VerifyCode();
		Session::getValue('code', $verify->getCode());
		Image::show($verify->generate());
	}
}