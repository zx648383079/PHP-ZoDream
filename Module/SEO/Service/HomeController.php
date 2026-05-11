<?php
declare(strict_types=1);
namespace Module\SEO\Service;

use Zodream\Infrastructure\Contracts\Http\Input;
use Module\Auth\Domain\Repositories\BanRepository;
use Module\SEO\Domain\Middleware\OptionMiddleware;

class HomeController extends Controller {


    public function methods() {
        return [
            'safety' => 'POST'
        ];
    }


	public function indexAction() {
		
	}

	public function safetyAction(Input $input, string $device_id) {
		if (BanRepository::isBanDevice($input->ip(), $device_id)) {
			return $this->renderFailure('验证不通过');
		}
		session()->set(OptionMiddleware::SafetyVerifyKey, $device_id);
		return $this->renderData(null, '验证通过!');
	}
}