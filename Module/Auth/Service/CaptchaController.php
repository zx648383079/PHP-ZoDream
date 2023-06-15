<?php
namespace Module\Auth\Service;

use Zodream\Image\Captcha;
use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Contracts\Http\Output;

class CaptchaController extends Controller {

    public function rules() {
        return [
            '*' => '*'
        ];
    }
	
	function indexAction(Request $request, Output $output) {
		$level = intval($request->get('level'));
		$captchaKey = $request->get('captcha_token');
		if (empty($level) && empty($captchaKey)) {
			$level = intval(session('level'));
		}
		$captcha = new Captcha();
		$captcha->setConfigs([
            'width' => intval($request->get('width', 100)),
            'height' => intval($request->get('height', 30)),
            'fontSize' => 20,
            'fontFamily' => (string)app_path()->file('data/fonts/YaHei.ttf')
        ]);
		$captcha->createCode(empty($captchaKey));
		if (!empty($captchaKey)) {
		    cache()->store('captcha')->set($captchaKey, strtolower($captcha->getCode()), 600);
        }
		return $output->image($captcha->generate($level));
	}

}