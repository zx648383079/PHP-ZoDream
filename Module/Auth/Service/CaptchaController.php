<?php
declare(strict_types=1);
namespace Module\Auth\Service;

use Module\Auth\Domain\Repositories\CaptchaRepository;

class CaptchaController extends Controller {

    public function rules() {
        return [
            '*' => '*'
        ];
    }
	
	function indexAction(int $level = 0,
                         string $captcha_token = '', string $type = '',
                         int $width = 0, int $height = 0) {
		return CaptchaRepository::generate($level, $captcha_token, $type, $width, $height);
	}

}