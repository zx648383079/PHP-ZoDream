<?php
declare(strict_types=1);
namespace Module\Auth\Service\Api;

use Module\Auth\Domain\Repositories\CaptchaRepository;
use Module\Auth\Service\Controller;

class CaptchaController extends Controller {

	public function indexAction(string $captcha_token, int $level = 0, string $type = '',
                         int $width = 0, int $height = 0) {
		return CaptchaRepository::generate($level, $captcha_token, $type, $width, $height);
	}

    public function verifyAction(string $captcha_token, string $captcha, string $type = '') {
        try {
            return $this->renderData(CaptchaRepository::verify($captcha, $captcha_token, $type, false));
        } catch (\Exception $ex) {
            if ($ex->getCode() === 1009) {
                return $this->renderFailure([
                    'message' => $ex->getMessage(),
                    'code' => $ex->getCode(),
                    'captcha_token' => CaptchaRepository::token($captcha_token)
                ]);
            }
            return $this->renderFailure($ex->getMessage());
        }
    }
}