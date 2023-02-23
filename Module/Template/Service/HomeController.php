<?php
namespace Module\Template\Service;

use Exception;
use Module\ModuleController;
use Module\Template\Domain\VisualEditor\VisualFactory;
use Zodream\Infrastructure\Contracts\Http\Output;

class HomeController extends ModuleController {

    public function indexAction(Output $output, int $site, int $id = 0) {
        try {
            $renderer = VisualFactory::entry($site, $id);
        } catch (Exception $ex) {
            return $this->redirectWithMessage('/', $ex->getMessage());
        }
        return $output->html($renderer->render());
    }


}