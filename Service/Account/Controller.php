<?php
declare(strict_types=1);
namespace Service\Account;

use Service\Controller as BaseController;
use Zodream\Disk\File;

abstract class Controller extends BaseController {

    protected File|string $layout = 'main';

	public function rules() {
		return array(
			'*' => '@'
		);
	}
}