<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Provider;

use Module\Legwork\Domain\Repositories\ProviderRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class HomeController extends Controller {

    public function indexAction() {
        try {
            return $this->render(
                ProviderRepository::getSelf()
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function saveAction(Input $input) {
        try {
            $data = $input->validate([
                'name' => 'required|string:0,100',
                'logo' => 'string:0,255',
                'tel' => 'required|string:0,30',
                'address' => 'required|string:0,255',
                'longitude' => 'string:0,50',
                'latitude' => 'string:0,50',
                'categories' => ''
            ]);
            return $this->render(
                ProviderRepository::save($data)
            );
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function applyAction(int|array $id) {
        try {
            ProviderRepository::applyCategory($id);
            return $this->renderData(true);
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }
}