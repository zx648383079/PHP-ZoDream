<?php
declare(strict_types=1);
namespace Module\Template\Service;

use Module\ModuleController;
use Module\Template\Domain\VisualEditor\VisualFactory;

class HomeController extends ModuleController {

    public function indexAction(int $site, int $id = 0) {
        try {
            return VisualFactory::cachePage(sprintf('%s/%s', $site, $id),
                function () use ($site, $id) {
                    return VisualFactory::entry($site, $id);
                });
        } catch (\Exception $ex) {
            return $this->redirectWithMessage('/', $ex->getMessage());
        }
    }


}