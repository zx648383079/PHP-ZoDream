<?php
namespace App\Controller;
	
use App\Lib\Verify;
use Endroid\QrCode\QrCode;
	
class ImageController extends Controller{
	
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
		$qrCode = new QrCode();
		$img = $qrCode
			->setText("Life is too short to be generating QR codes")
			->setSize(300)
			->setPadding(10)
			->setErrorCorrection('high')
			->setForegroundColor(array('r' => 0, 'g' => 0, 'b' => 0, 'a' => 0))
			->setBackgroundColor(array('r' => 255, 'g' => 255, 'b' => 255, 'a' => 0))
			->setLabel('My label')
			->setLabelFontSize(16)
			->render()
		;
		
		$this->showImg($img);
	}
}