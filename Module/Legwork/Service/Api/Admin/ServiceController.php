<?php
declare(strict_types=1);
namespace Module\Legwork\Service\Api\Admin;

use Module\Legwork\Domain\Repositories\ServiceRepository;

class ServiceController extends Controller {

    public function indexAction(string $keywords = '', int $user_id = 0) {
        return $this->renderPage(
            ServiceRepository::getList($keywords, $user_id)
        );
    }

    public function changeAction(int $id, int $status) {
        try {
            return $this->render(ServiceRepository::change($id, $status));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    
}