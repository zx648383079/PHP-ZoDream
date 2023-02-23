<?php
declare(strict_types=1);
namespace Module\Template\Service;

use Exception;
use Module\ModuleController;
use Module\Template\Domain\Model\PageWeightModel;
use Module\Template\Domain\VisualEditor\VisualWeight;
use Zodream\Infrastructure\Contracts\Http\Output;

class LazyController extends ModuleController {

    public function indexAction(Output $output, int $id) {
        try {
            $renderer = new VisualWeight(PageWeightModel::findOrThrow($id));
        } catch (Exception $ex) {
            return $this->redirectWithMessage('/', $ex->getMessage());
        }
        return $output->html($renderer->render(false, false));
    }


}