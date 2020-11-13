<?php
namespace Module\Auth\Service;

use Zodream\Image\Captcha;
use Zodream\Infrastructure\Http\Request;
use Zodream\Service\Factory;

class CaptchaController extends Controller {

    protected function rules() {
        return [
            '*' => '*'
        ];
    }
	
	function indexAction(Request $request) {
		$level = intval($request->get('level'));
		if (empty($level)) {
			$level = Factory::session('level');
		}
		$captcha = new Captcha();
		$captcha->setConfigs([
            'width' => intval($request->get('width', 100)),
            'height' => intval($request->get('height', 30)),
            'fontSize' => 20,
            'fontFamily' => (string)Factory::root()->file('data/fonts/YaHei.ttf')
        ]);
		$captcha->createCode(true);
		return Factory::response()->image($captcha->generate($level));
	}

}