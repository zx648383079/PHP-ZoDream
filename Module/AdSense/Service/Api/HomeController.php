<?php
declare(strict_types=1);
namespace Module\AdSense\Service\Api;

use Module\AdSense\Domain\Repositories\AdRepository;
use Module\ModuleController as Controller;

class HomeController extends Controller {

    public function indexAction(int $id = 0, int|string $position = 0) {
        if ($id > 0) {
            return $this->detailAction($id);
        }
        return $this->renderData(
            AdRepository::getList('', $position)
        );
    }

    public function detailAction(int $id) {
        try {
            return $this->render(AdRepository::get($id));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
    }

    public function bannerAction() {
        return $this->renderData(AdRepository::mobileBanners());
    }
}