<?php
declare(strict_types=1);
namespace Module\Catering\Service\Api\Merchant;

use Domain\Repositories\FileRepository;
use Module\Catering\Domain\Repositories\StoreRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class SettingController extends Controller {

	public function indexAction() {
        try {
            return $this->render(StoreRepository::merchantGet());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
	}

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,20',
                'keywords' => 'string:0,255',
                'description' => 'string:0,255',
                'logo' => 'string:0,255',
            ]);
            return $this->render(
                StoreRepository::merchantSave($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function logoAction() {
        try {
            $data = FileRepository::uploadImage();
            return $this->render(
                StoreRepository::merchantSave([
                    'logo' => $data['url']
                ])
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}