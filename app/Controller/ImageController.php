<?php
namespace App\Controller;
	
use App\Lib\Verify;
use App\Lib\QRcodeImg;
	
class ImageController extends Controller
{
	
	function index()
	{
		die(_('这里是生成的图片库。'));
	}

	/**
	 *生成验证码
     */
	function verify()
	{
		$verify = new Verify();
		$img = $verify->render();
		$this->showImg($img);
		$_SESSION['verify'] = $verify->code;
	}

	/**
	 * 生成二维码
	 *
	 * @throws \Endroid\QrCode\Exceptions\ImageFunctionUnknownException
     */
	function qrcode()
	{
		$img = QRcodeImg::show('wojiuzheyan');
		
		$this->showImg($img);
	}
}